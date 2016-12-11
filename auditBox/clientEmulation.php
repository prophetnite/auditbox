<?php

?>
<b>All tests use the api key:</b> 6F1ED002AB5595859014EBF0951522D9<br><br>

<h1>New Device Setup Test:</h1>
<form action="api.php?k=6F1ED002AB5595859014EBF0951522D9&r=10" method="post">
  Target ID:<br>
  <input type="text" name="target_id" value="8618ee57-27c2-4aaa-95f2-218f503a8398">
  <br>
  Device Label (doesn't matter much atm):<br>
  <input type="text" name="device_label" value="main_office">
  <br>
  Available Configs (<b>Must be JSON</b>):
  <textarea name="avail_configs">
    {"0":{"id":"daba56c8-73ec-11df-a475-002264764cea","label":"Full and fast"},"1":{"id":"698f691e-7489-11df-9d8c-002264764cea","label":"Full and fast ultimate"},"2":{"id":"708f25c4-7489-11df-8094-002264764cea","label":"Full and very deep"},"3":{"id":"74db13d6-7489-11df-91b9-002264764cea","label":"Full and very deep ultimate"}}
  </textarea>
  <br><br>
  <input type="submit" value="Submit">
</form>

<h1>Save Report Test:</h1>
<form action="api.php?k=6F1ED002AB5595859014EBF0951522D9&r=3" method="post">
  Task ID:<br>
  <input type="text" name="task_id" value="c9d0b718-7003-410e-b94b-f1557425c942">
  <br>
  Report Data:<br>
  <textarea name="report">
    Thank you http://loremfuckingipsum.com/
    Must-do is a good fucking master. You need to sit down and sketch more fucking ideas because stalking your ex on facebook isn’t going to get you anywhere. You won’t get good at anything by doing it a lot fucking aimlessly. Someday is not a fucking day of the week. Can we all just agree as the greater design community
  </textarea>
  <br><br>
  <input type="submit" value="Submit">
</form>
