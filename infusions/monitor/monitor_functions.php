<?php

/*
*
write_summary();
write_data_table('Last 24 hours', $hour); 
write_data_table('Last 30 days', $day);	
write_data_table('Last 12 months', $month);
*
*/

function kbytes_to_string($kb)
{
    $units = array('TB','GB','MB','KB');
    $scale = 1024*1024*1024;
    $ui = 0;

    while (($kb < $scale) && ($scale > 1))
    {
        $ui++;
        $scale = $scale / 1024;
    }   
    return sprintf("%0.2f %s", ($kb/$scale),$units[$ui]);
}

function write_summary()
{
    global $summary,$top,$day,$hour,$month;

    $trx = $summary['totalrx']*1024+$summary['totalrxk'];
    $ttx = $summary['totaltx']*1024+$summary['totaltxk'];

    //
    // build array for write_data_table
    //
    $sum[0]['act'] = 1;
    $sum[0]['label'] = 'This hour';
    $sum[0]['rx'] = $hour[0]['rx'];
    $sum[0]['tx'] = $hour[0]['tx'];

    $sum[1]['act'] = 1;
    $sum[1]['label'] = 'This day';
    $sum[1]['rx'] = $day[0]['rx'];
    $sum[1]['tx'] = $day[0]['tx'];

    $sum[2]['act'] = 1;
    $sum[2]['label'] = 'This month';
    $sum[2]['rx'] = $month[0]['rx'];
    $sum[2]['tx'] = $month[0]['tx'];

    $sum[3]['act'] = 1;
    $sum[3]['label'] = 'All time';
    $sum[3]['rx'] = $trx;
    $sum[3]['tx'] = $ttx;

    write_data_table('Summary', $sum);
    print "<br/>\n";
    write_data_table('Top 10 days', $top);
}


function write_data_table($caption, $tab)
{
    print "<table width=\"100%\" cellspacing=\"0\">\n";
    print "<caption>$caption</caption>\n";
    print "<tr>";
    print "<th class=\"label\" style=\"width:120px;\">&nbsp;</th>";
    print "<th class=\"label\">".'In'."</th>";
    print "<th class=\"label\">".'Out'."</th>";
    print "<th class=\"label\">".'Total'."</th>";  
    print "</tr>\n";

    for ($i=0; $i<count($tab); $i++)
    {
        if ($tab[$i]['act'] == 1)
        {
            $t = $tab[$i]['label'];
            $rx = kbytes_to_string($tab[$i]['rx']);
            $tx = kbytes_to_string($tab[$i]['tx']);
            $total = kbytes_to_string($tab[$i]['rx']+$tab[$i]['tx']);
            $id = ($i & 1) ? 'odd' : 'even';
            print "<tr>";
            print "<td class=\"label_$id\">$t</td>";
            print "<td class=\"numeric_$id\">$rx</td>";
            print "<td class=\"numeric_$id\">$tx</td>";
            print "<td class=\"numeric_$id\">$total</td>";
            print "</tr>\n";
         }
    }
    print "</table>\n";
}

function get_vnstat_data($iface)   
{
    global $hour,$day,$month,$top,$summary;
  
    $fd = popen("vnstat --dumpdb -i $iface", "r");
    $buffer = '';
    while (!feof($fd)) {
        $buffer .= fgets($fd);
    }
    $vnstat_data = explode("\n", $buffer);
    pclose($fd);

    $day = array();
    $hour = array();
    $month = array();
    $top = array();

    //
    // extract data
    //
    foreach($vnstat_data as $line) 
    {
        $d = explode(';', trim($line));
        if ($d[0] == 'd')
        {
            $day[$d[1]]['time']  = $d[2];
            $day[$d[1]]['rx']    = $d[3] * 1024 + $d[5];
            $day[$d[1]]['tx']    = $d[4] * 1024 + $d[6];
            $day[$d[1]]['act']   = $d[7];
            if ($d[2] != 0)
            {
                $day[$d[1]]['label'] = strftime('%d %B',$d[2]);
                $day[$d[1]]['img_label'] = strftime('%d', $d[2]);
            }
            else
            {
                $day[$d[1]]['label'] = '';
                $day[$d[1]]['img_label'] = '';          
            }           
        }
        else if ($d[0] == 'm')
        {
            $month[$d[1]]['time'] = $d[2];
            $month[$d[1]]['rx']   = $d[3] * 1024 + $d[5];
            $month[$d[1]]['tx']   = $d[4] * 1024 + $d[6];
            $month[$d[1]]['act']  = $d[7];
            if ($d[2] != 0)
            {
                $month[$d[1]]['label'] = strftime('%B %Y', $d[2]);
                $month[$d[1]]['img_label'] = strftime('%d', $d[2]);
            }
            else
            {
                $month[$d[1]]['label'] = '';
                $month[$d[1]]['img_label'] = '';            
            }
        }
        else if ($d[0] == 'h')
        {
            $hour[$d[1]]['time'] = $d[2];
            $hour[$d[1]]['rx']   = $d[3];
            $hour[$d[1]]['tx']   = $d[4];
            $hour[$d[1]]['act']  = 1;
            if ($d[2] != 0)
            {
                $st = $d[2] - ($d[2] % 3600);
                $et = $st + 3600;
                $hour[$d[1]]['label'] = strftime('%l%P'), $st).' - '.strftime('%l%P'), $et);
                $hour[$d[1]]['img_label'] = strftime('%l'), $d[2]);
            }
            else
            {
                $hour[$d[1]]['label'] = '';
                $hour[$d[1]]['img_label'] = '';
            }
        }
        else if ($d[0] == 't')
        {   
            $top[$d[1]]['time'] = $d[2];
            $top[$d[1]]['rx']   = $d[3] * 1024 + $d[5];
            $top[$d[1]]['tx']   = $d[4] * 1024 + $d[6];
            $top[$d[1]]['act']  = $d[7];
            $top[$d[1]]['label'] = strftime(T('%d %B %Y'), $d[2]);
            $top[$d[1]]['img_label'] = '';
        }
        else
        {
            $summary[$d[0]] = isset($d[1]) ? $d[1] : '';
        }
    }
    if (count($day) == 0)
        $day[0] = 'nodata';
    rsort($day);

    if (count($month) == 0)
        $month[0] = 'nodata';
    rsort($month);

    if (count($hour) == 0)
        $hour[0] = 'nodata';
    rsort($hour);
}

?>