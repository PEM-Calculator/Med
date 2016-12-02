@echo off

set run=..\vendor\bin\phpunit.bat --bootstrap .\bootstrap.php

echo -------------------------------------------
%run% u01\MilestoneTest.php

echo -------------------------------------------
%run% u01\TaskTest.php
