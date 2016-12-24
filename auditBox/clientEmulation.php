<?php
$apikey="6F1ED002AB5595859014EBF0951522D9";
?>



<b>All tests use the api key:</b> <?php echo $apikey; ?><br><br>
<h1>New Device Setup Test:</h1>

<form action='api.php?k=<?php echo $apikey; ?>&r=10' method="post">

<table border=1 width="40%">
  <tr><td>Target ID:</td><td><input type="text" name="target_id" value="8618ee57-27c2-4aaa-95f2-218f503a8398" length=30  style="width:100%" /> </td></tr>
  <tr><td>Device Label (doesn't matter much atm):</td><td><input type="text" name="device_label" value="main_office" style="width:100%" /></td></tr>
  <tr><td>Available Configs (<b>Must be JSON</b>):</td></tr>
  <tr><td colspan=2 style="height:225px">
  <textarea name="avail_configs" style="width:100%; height:100%">
    {"0":{"id":"daba56c8-73ec-11df-a475-002264764cea","label":"Full and fast"},"1":{"id":"698f691e-7489-11df-9d8c-002264764cea","label":"Full and fast ultimate"},"2":{"id":"708f25c4-7489-11df-8094-002264764cea","label":"Full and very deep"},"3":{"id":"74db13d6-7489-11df-91b9-002264764cea","label":"Full and very deep ultimate"}}
  </textarea></td></tr>
</table>

<input type="submit" value="Submit">
</form>





<h1>Save Report Test:</h1>

<form action="api.php?k=<?php echo $apikey; ?>&r=3" method="post">

<table border=1 width="40%">
  <tr><td>Task ID:</td><td><input type="text" name="task_id" value="c9d0b718-7003-410e-b94b-f1557425c942"  style="width:100%" > </td></tr>
  <tr><td>Report Data:</td></tr>
  <tr><td colspan=2 style="height:225px">
  <textarea name="report" style="width:100%; height:100%">
    <xml><test><thanks bitch=slut>Thank you http://loremfuckingipsum.com/
    Must-do is a good fucking master. You need to sit down and sketch more fucking ideas because stalking your ex on facebook isn’t going to get you anywhere. You won’t get good at anything by doing it a lot fucking aimlessly. Someday is not a fucking day of the week. Can we all just agree as the greater design community</thanks></test></xml>
  </textarea></td></tr>

</table>

<input type="submit" value="Submit">
</form>

