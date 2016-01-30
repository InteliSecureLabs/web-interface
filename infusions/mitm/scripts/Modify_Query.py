def request(context, flow):
 q = flow.request.get_query()
  if q:
   q["pineapple"] = ["rocks"]
   flow.request.set_query(q)