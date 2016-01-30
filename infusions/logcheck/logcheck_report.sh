#!/bin/sh

LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/usb/lib:/usb/usr/lib
PATH=$PATH:/usb/usr/bin:/usb/usr/sbin

MYPATH="$(dirname $0)/"
BIN=ssmtp
RESULT=0
_FILE=${MYPATH}events.tmp
_CUSTOM=${MYPATH}custom.tmp

grep -hv -e ^# ${MYPATH}rules/match -e ^$ > ${MYPATH}rules/match.tmp
grep -hv -e ^# ${MYPATH}rules/ignore -e ^$ > ${MYPATH}rules/ignore.tmp

cat ${MYPATH}events | grep -Ef ${MYPATH}rules/match.tmp | grep -vEf ${MYPATH}rules/ignore.tmp > ${MYPATH}events.tmp

TO=`cat ${MYPATH}logcheck.conf | grep "to" | awk -F = '{print $2}'`
FROM=`cat ${MYPATH}logcheck.conf | grep "from" | awk -F = '{print $2}'`
SUBJECT=`cat ${MYPATH}logcheck.conf | grep "subject" | awk -F = '{print $2}'`

echo -e "To: ${TO}" > ${MYPATH}mail.tmp
echo -e "From: ${FROM}" >> ${MYPATH}mail.tmp
echo -e "Subject: ${SUBJECT}" >> ${MYPATH}mail.tmp
echo -e "\n" >> ${MYPATH}mail.tmp

if [ -s ${_FILE} ]
then

echo -e "[Logcheck]" >> ${MYPATH}mail.tmp

cat ${MYPATH}events.tmp >> ${MYPATH}mail.tmp

rm -rf ${MYPATH}events

killall logread && echo ${MYPATH}logcheck.sh | at now

RESULT=1

fi

sh ${MYPATH}custom.sh >> ${MYPATH}custom.tmp

if [ -s ${_CUSTOM} ]
then

echo -e "\n" >> ${MYPATH}mail.tmp
echo -e "[Custom Script]" >> ${MYPATH}mail.tmp

cat ${MYPATH}custom.tmp >> ${MYPATH}mail.tmp

RESULT=1

fi

if [ ${RESULT} -eq 1 ]
then

${BIN} -t < ${MYPATH}mail.tmp

fi

rm -rf ${MYPATH}events.tmp
rm -rf ${MYPATH}mail.tmp
rm -rf ${MYPATH}rules/match.tmp
rm -rf ${MYPATH}rules/ignore.tmp
rm -rf ${MYPATH}custom.tmp
