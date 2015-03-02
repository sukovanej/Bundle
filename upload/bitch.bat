for /f "skip=34442 delims=*" %%a in (C:\Users\sukovanej\Downloads\sqlsql.sql) do (
echo %%a >>C:\Users\sukovanej\Downloads\newsql.sql
)
xcopy C:\Users\sukovanej\Downloads\newsql.sql C:\Users\sukovanej\Downloads\sqlsql.sql /y
del C:\Users\sukovanej\Downloads\newsql.sql /f /q