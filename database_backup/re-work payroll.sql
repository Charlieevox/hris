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
CALL spr_jamsostekcacl('2019/01');
CALL spr_taxcalcIncome('2019/01');
CALL spr_taxcalctiering('2019/01',1);

CALL spr_payrollCalculation('2019/01');
CALL spr_payrollCalculation('2019/02');
CALL spr_payrollCalculation('2019/03');
CALL spr_payrollCalculation('2019/04');
CALL spr_payrollCalculation('2019/05');
CALL spr_payrollCalculation('2019/06');
CALL spr_payrollCalculation('2019/07');
CALL spr_payrollCalculation('2019/08');
CALL spr_payrollCalculation('2019/09');
CALL spr_payrollCalculation('2019/10');
CALL spr_payrollCalculation('2019/11');
CALL spr_payrollCalculation('2019/12');




SELECT * FROM tr_working;
SELECT * FROM tr_payroll;
SELECT * FROM tr_payrolltaxincome;
SELECT * FROM tr_payrolltaxmonthlyproc;
SELECT * FROM tr_payrolltaxmonthlyproc;



