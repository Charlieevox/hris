-- generate Schedule
TRUNCATE TABLE ms_attendancewcalchead;
TRUNCATE TABLE ms_attendancewcalcdet;


INSERT INTO ms_attendancewcalchead
SELECT 
CONCAT(DATE_FORMAT(NOW(),'%Y/%m'),  '-' , a.id) 'id',
DATE_FORMAT(NOW(),'%Y/%m') 'period',
a.id,
'SYSTEM',
NOW(),
NULL,NULL
FROM ms_personnelhead a;

INSERT INTO ms_attendancewcalcdet
select 
CONCAT(DATE_FORMAT(NOW(),'%Y/%m'),  '-' , a.id) 'id',
DATE_FORMAT(NOW(),'%Y/%m') 'period',
a.id,
c.date,
b.shiftCode
from ms_personnelhead a
JOIN ms_attendanceshift b ON a.shiftCode = b.shiftCode
JOIN lk_calendar c ON c.date NOT IN (
	SELECT date FROM ms_attendanceHoliday
) AND c.date between CONCAT(DATE_FORMAT(NOW(),'%Y/%m'),'-01') AND LAST_DAY(NOW())
AND weekday(c.date) NOT IN (5,6)


