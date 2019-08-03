select TIMESTAMPDIFF(MINUTE,c.end,b.outTime),e.rate1,TIMESTAMPDIFF(MINUTE,c.end,b.outTime) * e.rate1 'lembur' from ms_attendancewcalcdet a
join ms_attendancewcalcactualdetail b on a.date = b.date
join ms_attendanceshift c on c.shiftCode = a.shiftCode
join ms_personnelhead d on d.id = b.nik
join ms_attendanceovertime e on e.overtimeId = d.overtimeId