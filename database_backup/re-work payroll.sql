TRUNCATE TABLE tr_payroll;
TRUNCATE TABLE tr_payrolltaxincome;
TRUNCATE TABLE tr_payrolltaxmonthlyproc;
TRUNCATE TABLE tr_workingtimecalc;
TRUNCATE TABLE tr_working;
TRUNCATE TABLE tr_loanproc;
TRUNCATE TABLE tr_payrollproc;


CALL spa_workingcalcdate('2019/01');
-- CALL spa_workingcalctime (periodDate);
-- CALL spl_loanProcess(periodDate);
CALL spr_insertPayrollComponent('2019/01');
CALL spr_jamsostekcacl(periodDate);
CALL spr_taxcalcIncome(periodDate);
CALL spr_taxcalctiering(periodDate,1);

call spr_payrollCalculation ('2019/01');
SELECT * FROM tr_payroll;

ms_payrollfixdetail