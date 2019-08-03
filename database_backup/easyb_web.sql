-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2017 at 06:01 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easyb_web`
--
CREATE DATABASE IF NOT EXISTS `easyb_web` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `easyb_web`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `spa_workingcalcdate`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spa_workingcalcdate` (IN `periodDate` VARCHAR(10))  BEGIN
DECLARE yearPeriod varchar(50);
DECLARE MonthPeriod varchar(50);
DECLARE LastPeriod varchar(50);

Select dateStart,dateEnd,overMonth into @dateStart, @dateEnd,@overMonth from ms_company;

SET yearPeriod = left(periodDate,4);
SET MonthPeriod = right(periodDate,2);

IF @overMonth = 1 THEN
SET LastPeriod = CONVERT(MonthPeriod, UNSIGNED INTEGER) - 1;
ELSE
SET LastPeriod = CONVERT(MonthPeriod, UNSIGNED INTEGER);
END IF;

DELETE FROM tr_working
WHERE period = periodDate;

SELECT b.type INTO @Setting FROM ms_company a
JOIN ms_payrollprorate b on b.prorateid = a.prorateSetting
where companyid = 1;

SELECT b.day INTO @Day FROM ms_company a
JOIN ms_payrollprorate b ON b.prorateid = a.prorateSetting
where companyid = 1;


DELETE FROM tr_working
WHERE period = periodDate;


IF @Setting = 1 THEN 
	INSERT INTO tr_working
	SELECT a.nik,a.period, @Day,0
	FROM ms_attendancewcalcdet a
    WHERE period = periodDate
	Group By a.period, a.nik;
ELSEIF @Setting = 2 THEN 
	INSERT INTO tr_working
	SELECT a.nik,a.period,count(a.id),0
	FROM ms_attendancewcalcdet a
	WHERE a.date between concat(yearPeriod,'/',LastPeriod,'/',@dateStart) AND concat(yearPeriod,'/',MonthPeriod,'/',@dateEnd)
	Group By a.nik;
ELSEIF @Setting = 3 THEN 
    INSERT INTO tr_working
	SELECT a.nik,a.period,DAY(LAST_DAY(CURDATE())),0
	FROM ms_attendancewcalcdet a
    WHERE period = periodDate
	Group By a.period, a.nik;
END IF;




UPDATE tr_working a
JOIN (
SELECT a.nik,a.period,count(b.id) 'Count' FROM ms_attendancewcalcdet a
JOIN ms_attendancewcalcactualdetail b on a.nik = b.nik and a.date = b.date
where a.date between concat(yearPeriod,'/',LastPeriod,'/',@dateStart) AND concat(yearPeriod,'/',MonthPeriod,'/',@dateEnd)
) z on a.nik = z.nik and a.period = periodDate
SET actual = z.count;

END$$

DROP PROCEDURE IF EXISTS `spa_workingcalctime`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spa_workingcalctime` (IN `periodDate` VARCHAR(10))  BEGIN

DELETE FROM tr_workingtime
WHERE DATE_FORMAT(date,'%m') = right(periodDate,2) AND DATE_FORMAT(date,'%Y') = LEFT(periodDate,4);

DELETE FROM tr_workingtimecalc
WHERE period = periodDate;

INSERT INTO tr_workingtime
SELECT d.id,a.date,a.inTime,a.outTime,b.shiftcode,c.start,c.END 'END',TIMEDIFF(a.outTime,a.inTime) 'gapAct',
TIMEDIFF(c.END,c.start) 'gapSch', TIMEDIFF(TIMEDIFF(a.outTime,a.inTime),TIMEDIFF(c.END,c.start)) 'gap',0,0,0,0
FROM ms_attendancewcalcactualdetail a 
JOIN ms_attendancewcalcdet b on a.nik = b.nik AND  b.date = a.date
JOIN ms_attendanceshift c on c.shitcode = b.shiftcode
JOIN ms_personnelhead d on d.id = a.nik
WHERE a.period = periodDate;



SELECT rate1,rate2,rate3,rate4 into @rate1, @rate2, @rate3, @rate4 FROM ms_attendanceovertime;

SELECT count(rate1) into @countJumlah FROM ms_attendanceovertime;

IF @countJumlah = 0 THEN
	UPDATE tr_workingtime
	SET OT1 = CASE WHEN hour(gap) > 1 then
	1.5
	ELSE
	0
	END
	WHERE gap > 0;

	UPDATE tr_workingtime
	SET OT2 = CASE WHEN hour(gap) > 2 then
	2
	ELSE
	0
	END
	WHERE gap > 0;

	UPDATE tr_workingtime
	SET OT3 = CASE WHEN hour(gap) > 3 then
	2
	ELSE
	0
	END
	WHERE gap > 0;
    
	UPDATE tr_workingtime
	SET OT4 = CASE WHEN hour(gap) > 4 then
	2
	ELSE
    0
	END
	WHERE gap > 0;
    
ELSE
	UPDATE tr_workingtime
	SET OT1 = CASE WHEN hour(gap) > 1 then
	@rate1
	ELSE
	0
	END
	WHERE gap > 0;

	UPDATE tr_workingtime
	SET OT2 = CASE WHEN hour(gap) > 2 then
	@rate2
	ELSE
	0
	END
	WHERE gap > 0;

	UPDATE tr_workingtime
	SET OT3 = CASE WHEN hour(gap) > 3 then
	@rate3
	ELSE
	0
	END
	WHERE gap > 0;
    
	UPDATE tr_workingtime
	SET OT4 = CASE WHEN hour(gap) > 4 then
	@rate4
	ELSE
    0
	END
	WHERE gap > 0;
    
END IF;


INSERT INTO tr_workingtimecalc
SELECT DATE_FORMAT(date,'%Y/%m'),nik,sum(OT1), sum(OT2),sum(OT3),sum(OT4),0
FROM tr_workingtime
WHERE DATE_FORMAT(date,'%m') = right(periodDate,2) AND DATE_FORMAT(date,'%Y') = LEFT(periodDate,4)
group by nik,DATE_FORMAT(date,'%Y/%m');

UPDATE tr_workingtimecalc
SET total = (Ot1+Ot2+Ot3+Ot4);

END$$

DROP PROCEDURE IF EXISTS `spl_loanProcess`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spl_loanProcess` (IN `periodDate` VARCHAR(10))  BEGIN

update ms_loan
set flagActive = 0
where principalPaid = (principal-downpayment);

Insert Into tr_loanproc
Select id,periodDate,(principal-downpayment)/term as Paid from ms_loan
where flagActive = 1 AND principalPaid <> principal AND
concat(registrationPeriod,'/01') < concat(periodDate,'/01') ;

update ms_loan a
join (select id,sum(principalPaid) as 'paid' from tr_loanproc group by id) b on b.id = a.id
set a.principalPaid = b.paid;

update ms_loan
set flagActive = 0
where principalPaid = (principal-downpayment);

END$$

DROP PROCEDURE IF EXISTS `spr_payrollCalculationdummy`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spr_payrollCalculationdummy` (IN `periodDate` VARCHAR(10))  BEGIN

DELETE FROM tr_payrolltaxmonthlyprocdummy
WHERE period = periodDate;

INSERT tr_payrolltaxmonthlyprocdummy 
SELECT 
period,
sequance,
nik,
npwp,
T01,
T02,
T03,
T04,
T05,
T06,
T07,
0,
T10,
0,
0,
0,
0,
0,
0,
ptkp,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0,
0
FROM tr_payrolltaxmonthlyproc
Where Period = periodDate;


CALL spr_taxcalctiering(periodDate,2);

END$$

DROP PROCEDURE IF EXISTS `spr_prorateCalc`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spr_prorateCalc` ()  BEGIN


Select b.type INTO @setting from ms_company a
JOIN ms_payrollprorate b on b.prorateid = a.prorateSetting;

SELECT b.day INTO @Day FROM ms_company a
JOIN ms_payrollprorate b ON b.prorateid = a.prorateSetting;

IF @setting = 1 THEN 
	SELECT @Day INTO @dayCalc;
ELSEIF @setting = 2 THEN 
	SELECT ''  ;
ELSEIF @setting = 3 THEN 
	SELECT DAY(LAST_DAY(CURDATE())) INTO @dayCalc;
END IF;

END$$

DROP PROCEDURE IF EXISTS `spr_taxcalctiering`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spr_taxcalctiering` (IN `periodDate` VARCHAR(10), `mode` VARCHAR(5))  BEGIN
DECLARE NotMatch INT DEFAULT 1;
DECLARE Count INT DEFAULT 0;

DECLARE T02Temp DECIMAL(18,2);
DECLARE sumT07 DECIMAL(18,2);
DECLARE curNik varchar(20);
DECLARE done INT DEFAULT 0;

DECLARE tableName VARCHAR(60);


/* CURSOR AWAL */
DECLARE cur CURSOR FOR 
SELECT nik,Sum(T07) 'T07' FROM tr_payrolltaxmonthlyproc
WHERE isFinal <> 1 AND LEFT(period,4) = LEFT(periodDate,4)
group by Nik;

DECLARE cur1 CURSOR FOR 
SELECT nik,Sum(T07) 'T07' FROM tr_payrolltaxmonthlyprocdummy
WHERE isFinal <> 1 AND LEFT(period,4) = LEFT(periodDate,4)
group by Nik;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

WHILE NotMatch > 0 DO 


/* --------------- DECLARE NAMA TABLE ------------------*/
IF mode = 1 THEN
	SET tableName = 'tr_payrolltaxmonthlyproc';
ELSE
	SET tableName = 'tr_payrolltaxmonthlyprocdummy';
END If;

/* -----------------------END----------------------------*/




/* ------------------------------------------ INITIAL INPUT ----------------------------------------------*/

SELECT taxSetting INTO @taxSetting 
FROM ms_company
where companyid = 1;

Select rate,maxAmount into @FERate, @FEMaxAmount from ms_payrollfunctionalexpenses
where id = 1;


SELECT ptkp,rate INTO @ptkp,@rate FROM ms_payrollptkp;

SET @SQL = CONCAT('
UPDATE ', tableName ,' a
JOIN ms_personnelhead b on b.id = a.nik
SET NPWP = 
CASE WHEN b.npwpNo = '''' THEN
0
ELSE
1
END');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @SQL = CONCAT('
UPDATE ', tableName ,' a
JOIN ms_personnelhead b on b.id = a.nik
SET PTKP = (b.depENDent * @rate ) + @ptkp
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


Select end,npwpRate,NonNpwpRate into @endT1, @npwpRateT1,@nonNpwpRateT1 from ms_payrolltaxrate where tieringcode = 'T1';
Select end,npwpRate,NonNpwpRate into @endT2, @npwpRateT2,@nonNpwpRateT2 from ms_payrolltaxrate where tieringcode = 'T2';
Select end,npwpRate,NonNpwpRate into @endT3, @npwpRateT3,@nonNpwpRateT3 from ms_payrolltaxrate where tieringcode = 'T3';
Select end,npwpRate,NonNpwpRate into @endT4, @npwpRateT4,@nonNpwpRateT4 from ms_payrolltaxrate where tieringcode = 'T4';



/* ------------------- SET PREV TAX --------------------*/
SET @SQL = CONCAT(
'UPDATE ', tableName ,' a
JOIN (SELECT nik,sum(pphAmount) amount FROM ', tableName , ' WHERE period  < "',periodDate,'" and LEFT(period,4) = ',LEFT(periodDate,4) ,' group by nik) b on b.nik = a.nik
SET prevTaxPaid = b.amount
WHERE period ="',periodDate,'"');

PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ------------------ SET WORK MONTH--------------------*/
UPDATE tr_payrolltaxmonthlyproc a
JOIN 

(SELECT nik,min(a.startDate) 'startDate',max(a.endDate) 'endDate' FROM ms_personnelcontract a
Group By Nik) b on b.nik = a.nik
SET workmonth = 

CASE WHEN year(b.startDate) =  Left(periodDate,4) THEN
	CASE WHEN  periodDate = concat(year(b.endDate),'/',DATE_FORMAT(b.endDate,'%m')) THEN
		TIMESTAMPDIFF(MONTH, b.startDate, b.enddate) +1
	ELSE
		TIMESTAMPDIFF(MONTH, b.startDate, concat(LEFT(periodDate,4),'/12/01')) +1
    END

WHEN year(b.startDate) <  Left(periodDate,4) THEN
    CASE WHEN  periodDate = concat(year(b.endDate),'/',DATE_FORMAT(b.endDate,'%m')) THEN
		TIMESTAMPDIFF(MONTH,  concat(LEFT(periodDate,4),'/01/01'),b.endDate) +1
    ELSE
    	TIMESTAMPDIFF(MONTH,  concat(LEFT(periodDate,4),'/01/01'),concat(LEFT(periodDate,4),'/12/01')) +1
	END
END
WHERE period = periodDate;

/* ------------------- SET SEQUANCE --------------------*/

SET @SQL = CONCAT('
UPDATE ',tableName,' a
JOIN (
SELECT nik,count(nik) as "count" FROM ',tableName,'
WHERE LEFT(period,4) = ',LEFT(periodDate,4),'
group by Nik
) b on b.nik = a.nik
SET 
a.sequance = b.count - 1 
WHERE period = "',periodDate,'"');

PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;




/* ------------------- SET PREV NETTO --------------------*/

SET @SQL = CONCAT('
UPDATE ',tableName, ' a
JOIN (SELECT nik,sum(netto) amount FROM ', tableName, ' WHERE period  <= "',periodDate,'" and LEFT(period,4) = LEFT("',periodDate,'" ,4)  group by nik) b on b.nik = a.nik
SET prevNetto = b.amount
WHERE period = "',periodDate,'"  AND ',Count = 0);

PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ------------------- SET PREV NETTO BJ --------------------*/

SET @SQL = CONCAT('
UPDATE ',tableName ,' a
JOIN (SELECT nik,sum(nettoBJ) amount FROM ', tableName, ' WHERE period  <= "',periodDate,'" and LEFT(period,4) = LEFT("',periodDate,'",4)  group by nik) b on b.nik = a.nik
SET prevNettoBJ = b.amount
WHERE period = "',periodDate,'"  AND ',Count = 0);

PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ---------------------------------------------END-------------------------------------------------*/




/* ---------------------------------------- CALCULATION -------------------------------------------*/


/* ------------------- NETTO BJ --------------------*/

SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET nettoBJ= floor(T01+T02+T03+T04+T05+T06)
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @SQL = CONCAT('
UPDATE ', tableName ,' a
JOIN (
SELECT nik,Sum(T07) as "T07" FROM tr_payrolltaxmonthlyproc
WHERE isFinal <> 1 AND LEFT(period,4) = LEFT("',periodDate,'",4)
group by Nik) b 
on a.nik = b.nik
SET NettoSumBJ= nettoBJ*(workmonth-sequance) + PrevNettoBJ + b.T07
WHERE period = "',periodDate,'"');

PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

/* ------------------- -NETTO-----------------------*/

SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET netto= floor(T01+T02+T03+T04+T05+T06)-(T10)
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



SET @SQL = CONCAT('
UPDATE ', tableName ,' a
JOIN (
SELECT nik,Sum(T07) as "T07" FROM tr_payrolltaxmonthlyproc
WHERE isFinal <> 1 AND LEFT(period,4) = LEFT("',periodDate,'",4)
group by Nik) b 
on a.nik = b.nik
SET NettoSum= Netto*(workmonth-sequance) + PrevNetto+b.T07
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ---------------------BIAYA JABATAN-----------------------*/
SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET 
biayajabatan = 
CASE WHEN NettoSumBJ * @FERate/100>@FEMaxAmount 
THEN 
(@FEMaxAmount/12)* workmonth
ELSE 
NettoSumBJ * @FERate/100 
END
WHERE period = "',periodDate,'" AND Isfinal = 0');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ------------------------PKP--------------------------*/
SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET PKP=floor((Nettosum-PTKP-BiayaJabatan)/1000)*1000  
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;




/* START GROOS UP */
IF @TaxSETting = 3 THEN

SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET T02 = 
CASE WHEN npwp = 1 THEN
	CASE 
		WHEN PKP <= 47500000 
		THEN
			FLOOR(((PKP - 0 )* 5 / 95 + 0)/WorkMonth)
		WHEN PKP > 47500000 
		THEN
			FLOOR(((PKP - 47500000) * 15 / 85 + 2500000) / WorkMonth)
		WHEN PKP > 217500000 THEN
			FLOOR(((PKP -217500000) * 25/75 + 32500000) / WorkMonth)
		WHEN PKP > 405000000 THEN
		FLOOR(((PKP -405000000) * 30/70 + 95000000) / WorkMonth)
	END

WHEN npwp = 0 THEN
	CASE 
		WHEN PKP <= 47000000 
		THEN
			FLOOR(((PKP - 0 )* 6 / 94 + 0)/WorkMonth)
		WHEN PKP > 47000000 
		THEN
			FLOOR(((PKP - 47000000) * 18 / 82 + 3000000) / WorkMonth)
		WHEN PKP > 211000000 THEN
			FLOOR(((PKP -211000000) * 30/70 + 39000000) / WorkMonth)
		WHEN PKP > 386000000 THEN
		FLOOR(((PKP -386000000) * 36/64 + 114000000) / WorkMonth)
	END
END

WHERE period = "',periodDate,'" AND T02 = 0');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



/*
UPDATE tr_payrolltaxmonthlyproc
SET T02 =  0
WHERE period = periodDate AND T02 < 0;
*/
SET @SQL = CONCAT('
UPDATE ', tableName ,' a
JOIN (
SELECT nik,sum(T02) as "T02" from tr_payrolltaxmonthlyproc
WHERE isFinal <> 1 AND LEFT(period,4) = LEFT(periodDate,4)
group by Nik
) b on b.nik = a.nik
SET 
a.t02 = a.t02 + b.t02
WHERE isFinal = 12 AND period = "',periodDate,'"');

/* Update */


/* ------------------- NETTO BJ --------------------*/

SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET nettoBJ= floor(T01+T02+T03+T04+T05+T06)
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @SQL = CONCAT('
UPDATE ', tableName ,' a
JOIN (
SELECT nik,Sum(T07) as "T07" FROM tr_payrolltaxmonthlyproc
WHERE isFinal <> 1 AND LEFT(period,4) = LEFT("',periodDate,'",4)
group by Nik) b 
on a.nik = b.nik
SET NettoSumBJ= nettoBJ*(workmonth-sequance) + PrevNettoBJ + b.T07
WHERE period = "',periodDate,'"');

PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



Select rate,maxAmount into @FERate, @FEMaxAmount from ms_payrollfunctionalexpenses
where id = 1;


/* ---------------------BIAYA JABATAN-----------------------*/

SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET 
biayajabatan = 
CASE WHEN NettoSumBJ * @FERate/100>@FEMaxAmount 
THEN 
(@FEMaxAmount/12)* workmonth
ELSE 
NettoSumBJ * @FERate/100 
END
WHERE period = "',periodDate,'" AND Isfinal = 0');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



/* ---------------------NETTO-----------------------*/

SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET netto= floor(T01+T02+T03+T04+T05+T06)-(T10)
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



SET @SQL = CONCAT('
UPDATE ', tableName ,' a
JOIN (
SELECT nik,Sum(T07) as "T07" FROM tr_payrolltaxmonthlyproc
WHERE isFinal <> 1 AND LEFT(period,4) = LEFT("',periodDate,'",4)
group by Nik) b 
on a.nik = b.nik
SET NettoSum= Netto*(workmonth-sequance) + PrevNetto+b.T07
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ------------------------PKP--------------------------*/

SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET PKP=floor((Nettosum-PTKP-BiayaJabatan)/1000)*1000  
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


END IF;
/* --------------------------------  END GROOS UP --------------------------------------*/





/* ---------------------------------------- TIERING -------------------------------------------*/

/* ------------------- -TIERING 1-----------------------*/

SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET PKP1=
CASE 
WHEN PKP>=  @endT1 
THEN 
@endT1 
ELSE 
PKP 
END
WHERE PKP>=0 AND period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ------------------- -TIERING 2-----------------------*/
SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET PKP2=
CASE 
WHEN PKP-(PKP1)>=@endT2 
THEN 
@endT2 
ELSE 
PKP-(PKP1) 
END
WHERE PKP>=PKP1 AND period =  "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ------------------- -TIERING 3-----------------------*/
SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET PKP3=
CASE WHEN PKP-(PKP1+PKP2)>=@endT3 
THEN 
@endT3 
ELSE PKP-(PKP1+PKP2) 
END
WHERE PKP>=PKP1+PKP2 AND period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ------------------- -TIERING 4-----------------------*/
SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET PKP4=PKP - (PKP1+PKP2+PKP3) 
WHERE PKP>=PKP1+PKP2+PKP3');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


/* ----------------------PPH CALC-----------------------*/
SET @SQL = CONCAT('
UPDATE ', tableName ,'
SET  PPHCalc=
CASE WHEN npwp = 1 THEN
FLOOR((((PKP1*@npwpRateT1)+(PKP2*@npwpRateT2)+(PKP3*@npwpRateT3)+(PKP4*@npwpRateT4))/100))
ELSE
FLOOR((((PKP1*@nonNpwpRateT1)+(PKP2*@nonNpwpRateT2)+(PKP3*@nonNpwpRateT3)+(PKP4*@nonNpwpRateT4))/100))
END
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



/* ----------------------PPH AMOUNT-----------------------*/
SET @SQL = CONCAT('
UPDATE ', tableName ,' a
JOIN ms_personnelhead b on b.id = a.nik
SET  pphAmount= 
CASE WHEN isFinal = 1 THEN
FLOOR(PPHCalc - prevTaxPaid)
ELSE
FLOOR((PPHCalc - prevTaxPaid) / (workmonth-sequance))
END
WHERE period = "',periodDate,'"');
PREPARE stmt FROM @SQL;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



IF @TaxSETting <> 3 THEN
SET NotMatch = 0;
ELSE

	SET @SQL = CONCAT('
	SELECT COUNT(*) INTO @notMatch FROM ', tableName ,' 
    WHERE T02<>PPhAmount AND IsFinal = 0');
    PREPARE stmt FROM @SQL;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;

    
    IF @notMatch = 0 or @notMatch Is Null  THEN
		SET NotMatch =  0;
        ELSE
	SET NotMatch = @notMatch ;
    
		SET @SQL = CONCAT('
       	UPDATE ', tableName ,'  
        SET T02=PPhAmount,PPhAmount=0 
        WHERE T02<>PPhAmount AND IsFinal = 0');
		PREPARE stmt FROM @SQL;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;        
        
    END IF;
    
END IF;

Set Count = Count + 1;

END WHILE;
END$$

DROP PROCEDURE IF EXISTS `spr_taxcalctieringfinal`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spr_taxcalctieringfinal` (IN `periodDate` VARCHAR(10))  BEGIN

SELECT RIGHT(periodDate,2);

END$$

DROP PROCEDURE IF EXISTS `sp_coba`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_coba` ()  BEGIN

SET @QueryString = "Select * from tr_payrolltaxmonthlyproc";
SET @QueryString = concat(@QueryString, " where NIK = 1;");

PREPARE QueryExec FROM @QueryString;
EXECUTE QueryExec;
DEALLOCATE PREPARE QueryExec;

END$$

DROP PROCEDURE IF EXISTS `sp_company_balance`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_company_balance` (IN `_ID` INT, `_mode` INT, `_user` VARCHAR(50))  BEGIN

IF _mode = 1 THEN
	INSERT INTO tr_companybalance (companyID,balanceDate,amount)
    SELECT companyID, NOW(), totalTopup
    FROM tr_topup 
    WHERE topupID = _ID;

ELSEIF _mode = 2 THEN

	SELECT CAST(value1 AS  DECIMAL (18,2)) AS VALUE1 INTO @value1
	FROM ms_setting 
	WHERE key1= 'create' AND key2 = 'user';
	
    SELECT companyID INTO @companyID
    FROM ms_user
    WHERE username = _user;
    
	INSERT INTO tr_companybalance (companyID,balanceDate,amount)
    VALUES (@companyID, NOW(), -1*@value1);
    
    END IF;
END$$

DROP PROCEDURE IF EXISTS `spr_insertPayrollComponent`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spr_insertPayrollComponent` (IN `periodDate` VARCHAR(10))  BEGIN


INSERT INTO tr_payroll
SELECT periodDate,nik,payrollcode,amount FROM ms_payrollincomedetail a
WHERE flagActive = 1 
AND  REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN startDate AND endDate
AND a.NIK In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate);

INSERT INTO tr_payroll
SELECT period,a.nik,'D03',floor((1/173*amount) * total) FROM tr_workingtimecalc a
JOIN ms_payrollincomedetail b on a.nik = b.nik AND b.payrollcode = 'A01' and b.flagActive = 1
AND a.NIK In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate);

UPDATE tr_payroll a
JOIN tr_working b on a.nik  = b.nik AND a.period = b.period
SET amount = 
((actual/schedule)*amount)
WHERE payrollcode in ('A01');



END$$

DROP PROCEDURE IF EXISTS `spr_jamsostekcacl`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spr_jamsostekcacl` (IN `periodDate` VARCHAR(10))  BEGIN


INSERT INTO tr_payroll


SELECT periodDate,a.id,'JKKCom',
CASE WHEN c.amount > b.maxratejkk THEN
(b.jkkcom/100)*b.maxratejkk
ELSE
(b.jkkcom/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1 
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)

UNION ALL


SELECT periodDate,a.id,'JKKEmp',
CASE WHEN c.amount > b.maxratejkk THEN
(b.jkkEmp/100)*b.maxratejkk
ELSE
(b.jkkEmp/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)

UNION ALL


SELECT periodDate,a.id,'JKMCom',
CASE WHEN c.amount > b.maxratejkm THEN
(b.jkmCom/100)*b.maxratejkm
ELSE
(b.jkmCom/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)

UNION ALL


SELECT periodDate,a.id,'JKMEmp',
CASE WHEN c.amount > b.maxratejkm THEN
(b.jkmEmp/100)*b.maxratejkm
ELSE
(b.jkmEmp/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)

UNION ALL


SELECT periodDate,a.id,'JHTCom',
CASE WHEN c.amount > b.maxratejht THEN
(b.jhtCom/100)*b.maxratejht
ELSE
(b.jhtCom/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)

UNION ALL


SELECT periodDate,a.id,'JHTEmp',
CASE WHEN c.amount > b.maxratejht THEN
(b.jhtEmp/100)*b.maxratejht
ELSE
(b.jhtEmp/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)

UNION ALL


SELECT periodDate,a.id,'JPKCom',
CASE WHEN c.amount > b.maxratejpk THEN
(b.jpkCom/100)*b.maxratejpk
ELSE
(b.jpkCom/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)

UNION ALL


SELECT periodDate,a.id,'JPKEmp',
CASE WHEN c.amount > b.maxratejpk THEN
(b.jpkEmp/100)*b.maxratejpk
ELSE
(b.jpkEmp/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)

UNION ALL


SELECT periodDate,a.id,'JPNCom',
CASE WHEN c.amount > b.maxratejpn THEN
(b.jpnCom/100)*b.maxratejpn
ELSE
(b.jpnCom/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)

UNION ALL


SELECT periodDate,a.id,'JPNEmp',
CASE WHEN c.amount > b.maxratejpn THEN
(b.jpnEmp/100)*b.maxratejpn
ELSE
(b.jpnEmp/100)*c.amount
END 
FROM ms_personnelhead a
JOIN ms_payrolljamsostek b ON a.jamsostekParm = b.jamsostekcode
JOIN ms_payrollincomedetail c ON c.payrollcode = b.payrollcodesource AND a.id = c.nik AND c.flagActive = 1
AND REPLACE(CONCAT(periodDate , "-01"),'/','-')  BETWEEN c.startDate AND c.endDate
WHERE a.id In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate);

END$$

DROP PROCEDURE IF EXISTS `sp_insert_journal`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_insert_journal` (IN `_transNum` VARCHAR(50), `_mode` INT, `_value` DECIMAL(18,2))  BEGIN

DECLARE curHeadID DECIMAL (18,0);
DECLARE curDetailID DECIMAL (18,0);
DECLARE curType VARCHAR(50);
DECLARE curAmount DECIMAL (18,2);

IF _mode = 1 THEN

	INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Purchase Order', purchaseNum, locationID, '', createdBy, NOW()
    FROM tr_purchaseorderhead 
    WHERE purchaseNum = _transNum;
    
	SELECT LAST_INSERT_ID() INTO curHeadID;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,e.coaNo,'IDR',1,SUM((a.qty*a.price*(100-a.discount)/100)),0
	FROM tr_purchaseorderdetail a
    JOIN tr_purchaseorderhead b on a.purchaseNum = b.purchaseNum
	JOIN ms_productdetail c on a.barcodeNumber = c.barcodeNumber
	JOIN ms_product d on c.productID = d.productID
    JOIN ms_category e on d.categoryID = e.categoryID
    WHERE a.purchaseNum = _transNum
    GROUP BY e.coaNo;
     
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,0,SUM((a.qty*a.price*(100-a.discount)/100)*((100+a.tax)/100))
	FROM tr_purchaseorderdetail a
    JOIN tr_purchaseorderhead b on a.purchaseNum = b.purchaseNum
	JOIN map_coa c on b.currencyID = c.currencyID AND c.transType = 'Payable'
    WHERE a.purchaseNum = _transNum
    GROUP BY c.coaNo;
    
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,SUM(a.subtotal*a.tax/100),0
	FROM tr_purchaseorderdetail a
    JOIN tr_purchaseorderhead b on a.purchaseNum = b.purchaseNum
	JOIN ms_tax c on b.taxID = c.taxID
    WHERE a.purchaseNum = _transNum AND b.taxRate IS NOT NULL
    GROUP BY c.coaNo;
    
ELSEIF _mode = 2 THEN

	INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Supplier Payment', paymentNum, locationID, '', createdBy, NOW()
    FROM tr_supplierpaymenthead
    WHERE paymentNum = _transNum;
    
    SELECT LAST_INSERT_ID() INTO curHeadID;
    
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,SUM(a.paymentTotal),0
	FROM tr_supplierpaymentdetail a
    JOIN tr_supplierpaymenthead b on a.paymentNum = b.paymentNum
	JOIN map_coa c on b.currencyID = c.currencyID AND c.transType = 'Payable'
    WHERE a.paymentNum = _transNum
    GROUP BY c.coaNo;
     
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,b.coaNo,'IDR',1,0,SUM(a.paymentTotal)
	FROM tr_supplierpaymentdetail a
    JOIN tr_supplierpaymenthead b on a.paymentNum = b.paymentNum
    WHERE a.paymentNum = _transNum
    GROUP BY b.coaNo;
    
ELSEIF _mode = 3 THEN
	
	INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Invoice', salesNum, locationID, '', createdBy, NOW()
    FROM tr_salesorderhead 
    WHERE salesNum = _transNum;
    
    SELECT LAST_INSERT_ID() INTO curHeadID;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1, CASE WHEN g.flagRecurring = 0 THEN 
    SUM((a.qty*a.subTotal*(100-a.discount)/100)*((100+a.tax)/100)) ELSE
    SUM((a.qty*a.price*(100-a.discount)/100)*((100+a.tax)/100)) END,0
	FROM tr_salesorderdetail a
    JOIN tr_salesorderhead b on a.salesNum = b.salesNum
	JOIN map_coa c on b.currencyID = c.currencyID AND c.transType = 'Receivables'
    JOIN ms_productdetail d on a.barcodeNumber = d.barcodeNumber
	JOIN ms_product e on d.productID = e.productID
    JOIN ms_category f on e.categoryID = f.categoryID
    JOIN lk_projecttype g on f.projecttypeID = g.projecttypeID
    WHERE a.salesNum = _transNum
    GROUP BY c.coaNo;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,e.coaNo,'IDR',1,0,CASE WHEN f.flagRecurring = 0 then 
    SUM((a.qty*a.subTotal*(100-a.discount)/100)) ELSE
    SUM((a.qty*a.price*(100-a.discount)/100))  END
	FROM tr_salesorderdetail a
    JOIN tr_salesorderhead b on a.salesNum = b.salesNum
	JOIN ms_productdetail c on a.barcodeNumber = c.barcodeNumber
	JOIN ms_product d on c.productID = d.productID
    JOIN ms_category e on d.categoryID = e.categoryID
    JOIN lk_projecttype f on e.projecttypeID = f.projecttypeID
    WHERE a.salesNum = _transNum
    GROUP BY e.coaNo;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,0,SUM(a.subtotal*a.tax/100)
	FROM tr_salesorderdetail a
    JOIN tr_salesorderhead b on a.salesNum = b.salesNum
	JOIN ms_tax c on b.taxID = c.taxID
    WHERE a.salesNum = _transNum AND b.taxRate IS NOT NULL
    GROUP BY c.coaNo;
    
ELSEIF _mode = 4 THEN

	INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Invoice Settlement', settlementNum, locationID, '', createdBy, NOW()
    FROM tr_clientsettlementhead
    WHERE settlementNum = _transNum;
    
    SELECT LAST_INSERT_ID() INTO curHeadID;
    
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,0,SUM(a.settlementTotal)
	FROM tr_clientsettlementdetail a
    JOIN tr_clientsettlementhead b on a.settlementNum = b.settlementNum
	JOIN map_coa c on b.currencyID = c.currencyID AND c.transType = 'Receivables'
    WHERE a.settlementNum = _transNum
    GROUP BY c.coaNo;
    
      
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,b.coaNo,'IDR',1,SUM(a.settlementTotal),0
	FROM tr_clientsettlementdetail a
    JOIN tr_clientsettlementhead b on a.settlementNum = b.settlementNum
    WHERE a.settlementNum = _transNum
    GROUP BY b.coaNo;
    
ELSEIF _mode = 5 THEN
	INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Cash In', cashInNum, locationID, '', createdBy, NOW()
    FROM tr_cashin
    WHERE cashInNum = _transNum;
    
    SELECT LAST_INSERT_ID() INTO curHeadID;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,cashAccount,'IDR',1,totalAmount,0
	FROM tr_cashin
    WHERE cashInNum = _transNum
    GROUP BY cashAccount;
     
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,incomeAccount,'IDR',1,0,cashInAmount
	FROM tr_cashin
    WHERE cashInNum = _transNum
    GROUP BY incomeAccount;
    
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,b.coaNo,'IDR',1,0,a.cashInAmount*a.taxRate/100
	FROM tr_cashin a
    JOIN ms_tax b on a.taxID = b.taxID
    WHERE a.cashInNum = _transNum
    GROUP BY b.coaNo;
    
    
ELSEIF _mode = 6 THEN
	INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Cash Out', cashOutNum, locationID, '', createdBy, NOW()
    FROM tr_cashout
    WHERE cashOutNum = _transNum;
    
   SELECT LAST_INSERT_ID() INTO curHeadID;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,expenseAccount,'IDR',1,cashOutAmount,0
	FROM tr_cashout
    WHERE cashOutNum = _transNum
    GROUP BY expenseAccount;
     
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,cashAccount,'IDR',1,0,totalAmount
	FROM tr_cashout
    WHERE cashOutNum = _transNum
    GROUP BY cashAccount;
    
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,b.coaNo,'IDR',1,a.cashOutAmount*a.taxRate/100,0
	FROM tr_cashout a
    JOIN ms_tax b on a.taxID = b.taxID
    WHERE a.cashOutNum = _transNum
    GROUP BY b.coaNo;
    
ELSEIF _mode = 7 THEN
	INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Asset Purchase', assetPurchaseNum, locationID, '', createdBy, NOW()
    FROM tr_assetpurchasehead
    WHERE assetPurchaseNum = _transNum;
    
    SELECT LAST_INSERT_ID() INTO curHeadID;
    
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,b.assetCOA,'IDR',1,SUM((a.qty*a.price*(100-a.discount)/100)),0
	FROM tr_assetpurchasedetail a
    JOIN ms_assetcategory b on a.assetCategoryID = b.assetCategoryID
    WHERE a.assetPurchaseNum = _transNum
    GROUP BY b.assetCOA;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,0,SUM((a.qty*a.price*(100-a.discount)/100)*((100+a.tax)/100))
	FROM tr_assetpurchasedetail a
    JOIN tr_assetpurchasehead b on a.assetPurchaseNum = b.assetPurchaseNum
    JOIN map_coa c on b.currencyID = c.currencyID AND c.transType = 'Payable'
    WHERE a.assetPurchaseNum = _transNum
    GROUP BY c.coaNo;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,SUM(a.subTotal*a.tax/100),0
	FROM tr_assetpurchasedetail a
    JOIN tr_assetpurchasehead b on a.assetpurchaseNum = b.assetpurchaseNum
    JOIN ms_tax c on b.taxID = c.taxID
    WHERE a.assetPurchaseNum = _transNum
    GROUP BY c.coaNo;
    
ELSEIF _mode = 8 THEN

	INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Asset Sales', assetSalesNum, locationID, '', createdBy, NOW()
    FROM tr_assetsaleshead
    WHERE assetSalesNum = _transNum;
    
    SELECT LAST_INSERT_ID() INTO curHeadID;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,b.assetCOA,'IDR',1,0,SUM(b.startingValue)
	FROM tr_assetsalesdetail a
    JOIN tr_assetdata b on a.assetID = b.assetID
    WHERE a.assetSalesNum = _transNum
    GROUP BY b.assetCOA;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,SUM((a.price*(100-a.discount)/100)),0
	FROM tr_assetsalesdetail a
    JOIN tr_assetsaleshead b on a.assetSalesNum = b.assetSalesNum
    JOIN map_coa c on b.currencyID = c.currencyID  AND  c.transType ='Asset Sales'
    WHERE a.assetSalesNum = _transNum
    GROUP BY c.coaNo;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,CASE WHEN a.price >= d.currentValue THEN 0 ELSE
    (SUM(d.startingValue)-SUM((a.price*(100-a.discount)/100)*((100+a.tax)/100))) END,
    CASE WHEN a.price >= d.currentValue THEN 
    -1*(SUM(d.startingValue)-SUM((a.price*(100-a.discount)/100)*((100+a.tax)/100))) ELSE
    0 END
	FROM tr_assetsalesdetail a
    JOIN tr_assetsaleshead b on a.assetSalesNum = b.assetSalesNum
    JOIN map_coa c on b.currencyID = c.currencyID  AND  c.transType ='Gain from sales of fixed asset'
	JOIN tr_assetdata d on a.assetID = d.assetID
    WHERE a.assetSalesNum = _transNum
    GROUP BY c.coaNo;
    
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,c.coaNo,'IDR',1,SUM(a.subTotal*a.tax/100),0
	FROM tr_assetsalesdetail a
    JOIN tr_assetsaleshead b on a.assetSalesNum = b.assetSalesNum
    JOIN ms_tax c on b.taxID = c.taxID
    WHERE a.assetSalesNum = _transNum
    GROUP BY c.coaNo;
    
    
ELSEIF _mode = 9 THEN
	
    DELETE FROM tr_assettransaction WHERE assetID = _transNum;
    
    INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Asset Dispose', assetID, locationID, '', 'Admin', NOW()
    FROM tr_assetdata
    WHERE assetID = _transNum;
    
   SELECT LAST_INSERT_ID() INTO curHeadID;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,assetCOA,'IDR',1,0,startingValue
	FROM tr_assetdata 
    WHERE assetID = _transNum
    GROUP BY assetCOA;
    
	INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT curHeadID,d.coaNo,'IDR',1,a.startingValue,0
	FROM tr_assetdata a
    JOIN tr_assetpurchasedetail b on a.assetCategoryID = b.assetCategoryID
    JOIN tr_assetpurchasehead c on b.assetPurchaseNum = c.assetPurchaseNum
    JOIN map_coa d on c.currencyID = d.currencyID AND d.transType ='Gain from sales of fixed asset' 
    WHERE a.assetID = _transNum
    GROUP BY d.coaNo;
    
ELSEIF _mode = 10  THEN
	 
    INSERT INTO tr_journalhead (journalDate,transactionType,refNum,locationID,notes,createdBy,createdDate)
    SELECT NOW(), 'Asset Depreciation', assetID, locationID, '', 'Admin', NOW()
    FROM tr_assetdata 
    WHERE assetID = _transNum;
    
   SELECT LAST_INSERT_ID() INTO curHeadID;
     
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT DISTINCT curHeadID,b.depCOA,'IDR',1,_value,0
	FROM tr_assetdata a
    JOIN ms_assetCategory b on a.assetCategoryID = b.assetCategoryID
    WHERE a.assetID = _transNum
    GROUP BY b.depCOA;
    
    INSERT INTO tr_journaldetail (journalHeadID,coaNo,currency,rate,drAmount,crAmount)
    SELECT DISTINCT curHeadID,b.expCOA,'IDR',1,0,_value
	FROM tr_assetdata a
    JOIN ms_assetCategory b on a.assetCategoryID = b.assetCategoryID
    WHERE a.assetID = _transNum
    GROUP BY b.expCOA;

    
END IF;

END$$

DROP PROCEDURE IF EXISTS `spr_payrollCalculation`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spr_payrollCalculation` (IN `periodDate` VARCHAR(10))  BEGIN


DELETE FROM tr_payroll
WHERE period = periodDate;

DELETE FROM tr_payrolltaxincome
WHERE period = periodDate;

DELETE FROM tr_payrolltaxmonthlyproc
WHERE period = periodDate;


DELETE FROM tr_workingtimecalc
WHERE period = periodDate;

DELETE FROM tr_working
WHERE period = periodDate;

DELETE FROM tr_loanproc
WHERE paymentPeriod = periodDate;


CALL spa_workingcalcdate(periodDate);
CALL spa_workingcalctime (periodDate);
CALL spl_loanProcess(periodDate);
CALL spr_insertPayrollComponent(periodDate);
CALL spr_jamsostekcacl(periodDate);
CALL spr_taxcalcIncome(periodDate);
CALL spr_taxcalctiering(periodDate,1);

END$$

DROP PROCEDURE IF EXISTS `spr_taxcalcIncome`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spr_taxcalcIncome` (IN `periodDate` VARCHAR(10))  BEGIN
 
INSERT INTO tr_payrolltaxincome
SELECT a.period,a.nik,c.dependent,
Sum(CASE b.articleId WHEN 'Article01' THEN Amount ELSE 0 End) 'T01',
Sum(CASE b.articleId WHEN 'Article03' THEN Amount ELSE 0 End) 'T03',
Sum(CASE b.articleId WHEN 'Article04' THEN Amount ELSE 0 End) 'T04',
Sum(CASE b.articleId WHEN 'Article05' THEN Amount ELSE 0 End) 'T05',
Sum(CASE b.articleId WHEN 'Article06' THEN Amount ELSE 0 End) 'T06',
Sum(CASE b.articleId WHEN 'Article07' THEN Amount ELSE 0 End) 'T07',
Sum(CASE b.articleId WHEN 'Article10' THEN Amount ELSE 0 End) 'T10',
0 'NettoBefore',
0 'PPhBefore'
FROM tr_payroll a
JOIN ms_payrollcomponent b on a.payrollcode = b.payrollcode
JOIN ms_personnelhead c on c.id = a.nik
WHERE period = periodDate
AND a.NIK In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)
group by a.nik, a.period;


INSERT INTO tr_payrolltaxmonthlyproc
SELECT a.period,0,a.nik,0,
Sum(CASE b.articleId WHEN 'Article01' THEN Amount ELSE 0 End) 'T01',
0 'T02',
Sum(CASE b.articleId WHEN 'Article03' THEN Amount ELSE 0 End) 'T03',
Sum(CASE b.articleId WHEN 'Article04' THEN Amount ELSE 0 End) 'T04',
Sum(CASE b.articleId WHEN 'Article05' THEN Amount ELSE 0 End) 'T05',
Sum(CASE b.articleId WHEN 'Article06' THEN Amount ELSE 0 End) 'T06',
Sum(CASE b.articleId WHEN 'Article07' THEN Amount ELSE 0 End) 'T07',
0 'BiayaJabatan',
Sum(CASE b.articleId WHEN 'Article10' THEN Amount ELSE 0 End) 'T10',
0,0,0,0,
0,
0,0,0,0,0,0,0,0,0,0,0,0,0
FROM tr_payroll a
JOIN ms_payrollcomponent b on a.payrollcode = b.payrollcode
WHERE period = periodDate
AND a.NIK In 
(Select z.nik from ms_personnelcontract z
where concat(periodDate,'/01')  between startDate AND endDate)
group by a.nik, a.period;



UPDATE tr_payrolltaxmonthlyproc a
JOIN (
Select b.nik,b.year,sum(netto) 'Netto', sum(taxPaid) 'TaxPaid'  from ms_payrolltaxbeforedetail a
JOIN ms_payrolltaxbefore b on a.id = b.id
Group By b.nik,b.year) b
on b.nik = a.nik
SET prevnetto = b.netto,
prevTaxPaid = b.taxpaid
Where sequance = 0;



END$$

DROP PROCEDURE IF EXISTS `spu_alert`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_alert` (IN `mode` VARCHAR(10))  BEGIN

DECLARE Curquery varchar(99);
DECLARE Curtitle varchar(99);
DECLARE CurAll varchar(9999) DEFAULT '';
DECLARE count INT DEFAULT 0;
DECLARE done INT DEFAULT 0;

DECLARE cur CURSOR FOR 
SELECT title,query FROM ms_alert;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
OPEN cur;
startLoop: LOOP
FETCH cur INTO Curtitle,Curquery;

	IF done = 1 THEN
		LEAVE startLoop;
	END IF;
	
    IF count = 0 THEN
		SET CurAll =  Curquery;
    ELSE
		SET CurAll =  CONCAT(CurAll,' UNION ALL ', Curquery);
	END IF;
	
    SET count = count + 1;

	END LOOP;
CLOSE cur;

IF mode = 1 THEN
	SET @query = CurAll;
	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
ELSEIF mode = 2 THEN
	OPEN cur;
	select FOUND_ROWS() ;
END IF;


END$$

DROP PROCEDURE IF EXISTS `sp_asset_data`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_asset_data` (IN `assetPurchaseNumber` VARCHAR(50))  BEGIN

DECLARE curAssetCategoryID INT;
DECLARE curAssetName varchar(50);
DECLARE curAssetCoa varchar(50);
DECLARE curDepCoa varchar(50);
DECLARE curExpCoa varchar(50);
DECLARE curDepLength INT;
DECLARE curValue DECIMAL(18,2);
DECLARE curRegisterDate DATETIME;
DECLARE curAbbreviation varchar(5);
DECLARE curQty INT;
DECLARE curTempQty DECIMAL(18,2);
DECLARE curCounter INT;
DECLARE curNewAssetID VARCHAR(50);
DECLARE curModifyCounter VARCHAR(20);
DECLARE curDateMonth VARCHAR(20);
DECLARE curDateYear VARCHAR(20);
DECLARE curLocationID INT;

	DECLARE done INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
    
	SELECT a.assetCategoryID, a.assetName, b.assetCOA, b.depCOA, b.expCOA,
		   b.depLength, c.createdDate, (a.price*(100-a.discount)/100)*((100+a.tax)/100) AS price,
           a.qty, MONTH(c.assetPurchaseDate), YEAR(c.assetPurchaseDate), b.abbreviation, c.locationID
	FROM tr_assetpurchasedetail a
	JOIN ms_assetcategory b on a.assetCategoryID = b.assetCategoryID
    JOIN tr_assetpurchasehead c on a.assetPurchaseNum = c.assetPurchaseNum
	WHERE a.assetPurchaseNum = assetPurchaseNumber;
	
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cur;
	startLoop: LOOP
	FETCH cur INTO curAssetCategoryID,curAssetName,curAssetCoa,curDepCoa,curExpCoa,
				curDepLength,curRegisterDate,curValue,curQty,curDateMonth,curDateYear,curAbbreviation,curLocationID;
        
    SET curTempQty = 0;
    SET curCounter = 0;
    SET curModifyCounter = '';

    SELECT IFNULL(MAX(SUBSTRING_INDEX(assetID,'.',-1)),0) 
    INTO curCounter
    FROM tr_assetdata
    WHERE assetCategoryID=curAssetCategoryID AND MONTH(registerDate) = curDateMonth 
    AND YEAR(registerDate) = curDateYear;
    
    WHILE (curTempQty < curQty) DO
    SET curCounter = curCounter + 1;
    SET curModifyCounter = RIGHT(CONCAT('0000',curCounter),4);
	SET curNewAssetID = CONCAT(curAbbreviation,'.',RIGHT(CONCAT('0',curDateMonth),2),'.',curDateYear,'.',curModifyCounter);
	
	IF done = 1 THEN
		LEAVE startLoop;
	END IF;
	
	INSERT INTO tr_assetdata (assetID, assetCategoryID, assetName, locationID, assetCOA, depCOA, expCOA,
				depLength, startingValue, currentValue, depOccurence, registerDate, 
                startDepDate, flagActive)
	VALUES(curNewAssetID,curAssetCategoryID,curAssetName,curLocationID,curAssetCoa,curDepCoa,curExpCoa,
		   curDepLength,curValue,curValue,0,curRegisterDate,NULL,0);
           SET curTempQty = curTempQty + 1;
	
    END while;
    
	END LOOP;
	CLOSE cur;
END$$

DROP PROCEDURE IF EXISTS `sp_asset_depreciation`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_asset_depreciation` (IN `depDate` DATETIME)  BEGIN
	DECLARE curAssetID VARCHAR(50);
	DECLARE curDepLength INT;
	DECLARE numOfDep INT;
	DECLARE curDepLengthNew INT;
	DECLARE curDepOccurence INT;
	DECLARE curMonthDepVal DECIMAL(18,2);
	DECLARE done INT DEFAULT 0;
    DECLARE depLengthNew INT;
	DECLARE cur CURSOR FOR 
	SELECT a.assetID, a.startingValue/a.depLength, TIMESTAMPDIFF(MONTH,a.startDepDate,depDate)-a.depOccurence, a.depLength, a.depOccurence
	FROM tr_assetdata a
	WHERE a.startDepDate IS NOT NULL AND a.FlagActive = 1 AND a.depOccurence < a.depLength;
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cur;
	startLoop: LOOP
        FETCH cur INTO curAssetID, curMonthDepVal, curDepLength, curDepLengthNew, curDepOccurence;
         
		IF done = 1 THEN
			LEAVE startLoop;
		END IF;
        
        SET numOfDep = 0;
		IF curDepLength > curDepLengthNew THEN
			SET depLengthNew = curDepLengthNew;
		ELSE
			SET depLengthNew = curDepLength;
		END IF;
        
        WHILE(numOfDep < depLengthNew) DO
			SET numOfDep = numOfDep + 1;
			
			UPDATE tr_assetdata 
            SET currentValue = currentValue - curMonthDepVal, depOccurence = depOccurence + 1
            WHERE assetID = curAssetID;
            
            SELECT currentValue INTO @afterValue
            FROM tr_assetdata
            WHERE assetID = curAssetID;
            
            SELECT currentValue+curMonthDepVal INTO @beforeValue
            FROM tr_assetdata
            WHERE assetID = curAssetID;
			
            INSERT INTO tr_assettransaction VALUES (NULL, NOW(),curAssetID, 'Asset Depreciation', @beforeValue, curMonthDepVal, @afterValue, NOW());

			CALL sp_insert_journal(curAssetID,10,curMonthDepVal);
		END WHILE;
        
	END LOOP;

	CLOSE cur;
END$$

DROP PROCEDURE IF EXISTS `sp_asset_sales`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_asset_sales` (IN `assetSalesNumber` VARCHAR(50))  BEGIN

DECLARE curDate DATETIME;
DECLARE curAssetID varchar(50);
DECLARE curAssetValueBefore DECIMAL(18,2);
DECLARE curAssetValueAfter DECIMAL(18,2);

	DECLARE done INT DEFAULT 0;
	DECLARE cur CURSOR FOR 

	SELECT a.assetID,b.startingValue,b.currentValue,c.createdDate
	FROM tr_assetsalesdetail a
	JOIN tr_assetdata b on a.assetID = b.assetID
    JOIN tr_assetsaleshead c on a.assetSalesNum = c.assetSalesNum
	WHERE a.assetSalesNum = assetSalesNumber;
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cur;
	startLoop: LOOP
	FETCH cur INTO curAssetID,curAssetValueBefore,curAssetValueAfter,curDate;
    
     IF done = 1 THEN
		LEAVE startLoop;
		END IF;
        
	INSERT INTO tr_assettransaction VALUES(NULL,curDate,curAssetID,'Asset Sales',curAssetValueBefore,curAssetValueBefore,
		   curAssetValueAfter,curDate);
           
	UPDATE tr_assetdata set currentValue = 0,flagActive = 0 WHERE assetID = curAssetID;
         
	END LOOP;
	CLOSE cur;
    
   
END$$

DROP PROCEDURE IF EXISTS `sp_client_settlement`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_client_settlement` (IN `settlementNumber` VARCHAR(50))  BEGIN
DECLARE curSalesNum VARCHAR(50);
	DECLARE curSalesTotal DECIMAL(18,2);
	DECLARE done INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
	SELECT a.salesNum, b.grandTotal
	FROM tr_clientsettlementdetail a
	JOIN tr_salesorderhead b on a.salesNum = b.salesNum
	WHERE a.settlementNum = settlementNumber;
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cur;
	startLoop: LOOP
		FETCH cur INTO curSalesNum, curSalesTotal;
		SELECT SUM(settlementTotal) INTO @curSettlementTotal
		FROM tr_clientsettlementdetail 
		WHERE salesNum = curSalesNum
		GROUP BY salesNum;

		IF @curSettlementTotal >= curSalesTotal THEN
			UPDATE tr_salesorderhead SET Status = 5 where salesNum = curSalesNum;
		ELSE 
			UPDATE tr_salesorderhead SET Status = 4 where salesNum = curSalesNum;
		END IF;
        
        IF done = 1 THEN
			LEAVE startLoop;
		END IF;
	END LOOP;

	CLOSE cur;
END$$

DROP PROCEDURE IF EXISTS `sp_copy_stored_procedure`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_copy_stored_procedure` (IN `_user` VARCHAR(50))  BEGIN
	DECLARE done INT DEFAULT FALSE; 
    DECLARE spesificname TEXT;
    DECLARE spesificbody TEXT;
	DECLARE spesificparam TEXT;
    DECLARE cur CURSOR FOR (
		SELECT name, CONVERT(body USING utf8) AS body,
        CONVERT(param_list USING utf8) AS param_list
		FROM mysql.proc WHERE db = 'easyb_web'
        ORDER BY name ASC
    );
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    START TRANSACTION;
	OPEN cur; 
    startLoop: LOOP 
            FETCH cur INTO spesificname, spesificbody, spesificparam; 
            IF done THEN 
                    LEAVE startLoop; 
            END IF; 
            
			                                    
            set @createsp := concat("USE ",_user,"; CREATE PROCEDURE `",spesificname,"`(",spesificparam,") ",spesificbody," ");
            set @createsp = replace(@createsp, '\n',' ');
            select @createsp;
                         prepare createsp from @createsp; 
             execute createsp;
    END LOOP; 
    CLOSE cur;
    COMMIT;

END$$

DROP PROCEDURE IF EXISTS `sp_customer_settlement`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_customer_settlement` (IN `settlementNumber` VARCHAR(50))  BEGIN
DECLARE curSalesNum VARCHAR(50);
	DECLARE curSalesTotal DECIMAL(18,2);
	DECLARE done INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
	SELECT a.salesNum, b.grandTotal
	FROM tr_customersettlementdetail a
	JOIN tr_salesorderhead b on a.salesNum = b.salesNum
	WHERE a.settlementNum = settlementNumber;
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cur;
	startLoop: LOOP
		FETCH cur INTO curSalesNum, curSalesTotal;
		SELECT SUM(settlementTotal) INTO @curSettlementTotal
		FROM tr_customersettlementdetail 
		WHERE salesNum = curSalesNum
		GROUP BY salesNum;

		IF @curSettlementTotal >= curSalesTotal THEN
			UPDATE tr_salesorderhead SET Status = 4 where salesNum = curSalesNum;
		ELSE 
			UPDATE tr_salesorderhead SET Status = 3 where salesNum = curSalesNum;
		END IF;
        
        IF done = 1 THEN
			LEAVE startLoop;
		END IF;
	END LOOP;

	CLOSE cur;
END$$

DROP PROCEDURE IF EXISTS `sp_database`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_database` (IN `_user` VARCHAR(50))  BEGIN

  DECLARE done INT DEFAULT FALSE; 
    DECLARE tablename TEXT;
    DECLARE cur CURSOR FOR (
        SELECT table_name
        FROM information_schema.tables
        WHERE
            table_schema='easyb_web' AND
            table_type = 'base table'
        ORDER BY table_name ASC
    );
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    START TRANSACTION;
       set @createinstance := concat("CREATE DATABASE `",_user,"`"); 
    prepare createinstance from @createinstance; 
    execute createinstance;

	OPEN cur; 
    startLoop: LOOP 
            FETCH cur INTO tablename; 
            IF done THEN 
                    LEAVE startLoop; 
            END IF; 

            set @createtable := concat("CREATE TABLE `",_user,"`.`",tablename,"` LIKE `easyb_web`.`",tablename,"`");
            prepare createtable from @createtable; 
            execute createtable;
            
            set @inserttable := concat("INSERT INTO `",_user,"`.`",tablename,"` SELECT * FROM `easyb_web`.`",tablename,"`");
            prepare inserttable from @inserttable; 
            execute inserttable;
    END LOOP; 
    CLOSE cur;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `sp_delete_client_settlement`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_delete_client_settlement` (IN `settlementNumber` VARCHAR(50), `_mode` INT)  BEGIN

	DECLARE curSalesNum VARCHAR(50);
	DECLARE curSalesTotal DECIMAL(18,2);
	DECLARE done INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
	SELECT a.salesNum, b.grandTotal
	FROM tr_clientsettlementdetail a
	JOIN tr_salesorderhead b on a.salesNum = b.salesNum
	WHERE a.settlementNum = settlementNumber;
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cur;
	startLoop: LOOP
		FETCH cur INTO curSalesNum, curSalesTotal;
        
		SELECT a.grandTotal - IFNULL(b.settlementTotal,0) INTO @curSettlementTotal
		FROM tr_salesorderhead a
        LEFT JOIN
        (
			SELECT salesNum, SUM(settlementTotal) AS settlementTotal
            FROM tr_clientsettlementdetail WHERE 
            settlementNum <> settlementNumber
        )b on a.salesNum = b.salesNum
		WHERE a.salesNum = curSalesNum;	

		IF @curSettlementTotal = curSalesTotal THEN
			UPDATE tr_salesorderhead SET Status = 3 where salesNum = curSalesNum;
		ELSE 
			UPDATE tr_salesorderhead SET Status = 4 where salesNum = curSalesNum;
		END IF;
        
        IF done = 1 THEN
			LEAVE startLoop;
		END IF;
	END LOOP;

	CLOSE cur;
    
	DELETE FROM tr_clientsettlementdetail where settlementNum = settlementNumber;
    
    DELETE FROM tr_accountreceivable where referenceNum = settlementNumber;
   
    DELETE a
	FROM tr_journaldetail a
	JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
	WHERE b.refNum = settlementNumber;
    
	DELETE FROM tr_journalhead where refNum = settlementNumber;
    
    IF _mode = 1 THEN
    DELETE FROM tr_clientsettlementhead where settlementNum = settlementNumber;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_delete_purchaseorder`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_delete_purchaseorder` (IN `purchaseNumber` VARCHAR(50))  BEGIN
    DECLARE curPaymentNum VARCHAR(50);
	DECLARE done INT DEFAULT 0;
    
    DECLARE cur CURSOR FOR 
	SELECT paymentNum
	FROM tr_supplierpaymentdetail
	WHERE purchaseNum = purchaseNumber;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cur;
	startLoop: LOOP
		FETCH cur INTO curPaymentNum;
    
		IF done = 1 THEN
			LEAVE startLoop;
		END IF;
        
	SELECT sum(paymentTotal) INTO @grandTotal
	FROM tr_supplierpaymentdetail
	WHERE paymentNum = curPaymentNum AND purchaseNum = purchaseNum;
		
	UPDATE tr_supplierpaymenthead 
    SET grandTotal = grandTotal - @grandTotal 
    where paymentNum = curPaymentNum;
    
	END LOOP;

	CLOSE cur;
    
	DELETE FROM tr_supplierpaymentdetail WHERE PurchaseNum = purchaseNumber;
        
	
       
END$$

DROP PROCEDURE IF EXISTS `sp_delete_salesorder`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_salesorder` (IN `salesNumber` VARCHAR(50))  BEGIN
	DECLARE curSettlementNum VARCHAR(50);
	DECLARE done INT DEFAULT 0;
    
    DECLARE cur CURSOR FOR 
    SELECT settlementNum
	FROM tr_clientsettlementdetail
	WHERE salesNum = salesNumber;
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cur;
	startLoop: LOOP
		FETCH cur INTO curSettlementNum;
        
        IF done = 1 THEN
			LEAVE startLoop;
		END IF;
        
		SELECT SUM(settlementTotal) INTO @grandTotal
		FROM tr_clientsettlementdetail
		WHERE settlementNum = curSettlementNum AND salesNum = salesNum;
        
        UPDATE tr_clientsettlementhead 
        SET grandTotal = grandTotal - @grandTotal 
        WHERE settlementNum = curSettlementNum;
	END LOOP;

	CLOSE cur;
    
    DELETE FROM tr_clientsettlementdetail WHERE salesNum = salesNumber;
END$$

DROP PROCEDURE IF EXISTS `sp_delete_supplier_payment`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_delete_supplier_payment` (IN `paymentNumber` VARCHAR(50), `_mode` INT)  BEGIN

	DECLARE curPurchaseNum VARCHAR(50);
	DECLARE curPurchaseTotal DECIMAL(18,2);
	DECLARE done INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
	SELECT a.purchaseNum, b.grandTotal
	FROM tr_supplierpaymentdetail a
	JOIN tr_purchaseorderhead b on a.purchaseNum = b.purchaseNum
	WHERE a.paymentNum = paymentNumber;
    	
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET SQL_SAFE_UPDATES=0;
	OPEN cur;
	startLoop: LOOP
		FETCH cur INTO curPurchaseNum, curPurchaseTotal;
        
		SELECT a.grandTotal - IFNULL(b.paymentTotal,0) INTO @curPaymentTotal
		FROM tr_purchaseorderhead a
        LEFT JOIN
        (
			SELECT purchaseNum, SUM(paymentTotal) AS paymentTotal
            FROM tr_supplierpaymentdetail WHERE 
            paymentNum <> paymentNumber
        )b on a.purchaseNum = b.purchaseNum
		WHERE a.purchaseNum = curPurchaseNum;	
		
		
		IF @curPaymentTotal = curPurchaseTotal THEN
			UPDATE tr_purchaseorderhead SET Status = 3 where purchaseNum = curPurchaseNum;
		ELSE 
			UPDATE tr_purchaseorderhead SET Status = 4 where purchaseNum = curPurchaseNum;
		END IF;
        
        IF done = 1 THEN
			LEAVE startLoop;
		END IF;
	END LOOP;
	
	CLOSE cur;
    
	DELETE FROM tr_supplierpaymentdetail where paymentNum = paymentNumber;
    
    DELETE FROM tr_accountpayable where referenceNum = paymentNumber;
   
    DELETE a
	FROM tr_journaldetail a
	JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
	WHERE b.refNum = paymentNumber;
    
	DELETE FROM tr_journalhead where refNum = paymentNumber;
    
    IF _mode = 1 THEN
    DELETE FROM tr_supplierpaymenthead where paymentNum = paymentNumber;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_supplier_payment`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_supplier_payment` (IN `paymentNumber` VARCHAR(50))  BEGIN
DECLARE curPurchaseNum VARCHAR(50);
	DECLARE curPurchaseTotal DECIMAL(18,2);
	DECLARE done INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
	SELECT a.purchaseNum, b.grandTotal
	FROM tr_supplierpaymentdetail a
	JOIN tr_purchaseorderhead b on a.purchaseNum = b.purchaseNum
	WHERE a.paymentNum = paymentNumber;
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN cur;
	startLoop: LOOP
		FETCH cur INTO curPurchaseNum, curPurchaseTotal;
		SELECT SUM(paymentTotal) INTO @curPaymentTotal
		FROM tr_supplierpaymentdetail 
		WHERE purchaseNum = curPurchaseNum
		GROUP BY purchaseNum;

		IF @curPaymentTotal >= curPurchaseTotal THEN
			UPDATE tr_purchaseorderhead SET Status = 5 where purchaseNum = curPurchaseNum;
		ELSE 
			UPDATE tr_purchaseorderhead SET Status = 4 where purchaseNum = curPurchaseNum;
		END IF;
        
        IF done = 1 THEN
			LEAVE startLoop;
		END IF;
	END LOOP;

	CLOSE cur;
END$$

DROP PROCEDURE IF EXISTS `sp_testsp`$$
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `sp_testsp` (IN `_user` VARCHAR(50))  BEGIN
	DECLARE done INT DEFAULT FALSE; 
    DECLARE spesificname TEXT;
    DECLARE spesificbody TEXT;
	DECLARE spesificparam TEXT;
    DECLARE cur CURSOR FOR (
		SELECT name, CONVERT(body USING utf8) AS body,
        CONVERT(param_list USING utf8) AS param_list
		FROM mysql.proc WHERE db = 'easyb_web'
        ORDER BY name ASC
    );
    
     set @createsp := concat("
		USE ",_user,";
		DELIMITER $$
		CREATE PROCEDURE sp_test()
		BEGIN
			INSERT INTO new.emp SELECT * FROM old.users;
		END
     ");
    
     set @createsp = replace(@createsp, '\n',' ');
		select @createsp;
	 prepare createsp from @createsp; 
	
	 execute createsp;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `lk_accesscontrol`
--

DROP TABLE IF EXISTS `lk_accesscontrol`;
CREATE TABLE `lk_accesscontrol` (
  `accessID` varchar(10) NOT NULL,
  `description` varchar(50) NOT NULL,
  `node` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_accesscontrol`
--

INSERT INTO `lk_accesscontrol` (`accessID`, `description`, `node`, `icon`) VALUES
('', '', '', ''),
('A', 'Personnel', 'Personnel', 'fa-database'),
('A.1', 'Profile Data', '/personnel-head', 'fa-user'),
('B', 'Attendance', 'Attendance', 'fa-tasks'),
('B.1', 'Shift Parameter', '/attendance-shift', 'fa-wrench'),
('B.2', 'Overtime Parameter', '/attendance-overtime', 'fa-wrench'),
('B.3', 'Holiday Date', '/attendance-holiday', 'fa-wrench'),
('B.7', 'Work Schedule', '/attendance-w-calc-head', 'fa-calendar-o'),
('B.8', 'Work Schedule Actual ', '/attendance-w-calc-actual-head', 'fa-calendar-o'),
('C', 'Payroll', 'Payroll', 'fa-money'),
('C.1', 'Payroll Component', '/payroll-component', 'fa-wrench'),
('C.2', 'Tax Rate', '/payroll-tax-rate', 'fa-wrench'),
('C.3', 'PTKP Rate', '/payroll-ptkp', 'fa-wrench'),
('C.4', 'Jamsostek Parameter', '/payroll-jamsostek', 'fa-wrench'),
('C.5', 'Prorate Parameter', '/payroll-prorate', 'fa-wrench'),
('C.6', 'Functional Expenses', '/payroll-functional-expenses', 'fa-wrench'),
('C.7', 'Income', '/payroll-income', 'fa-cube'),
('C.8', 'Income Tax Before', '/payroll-tax-before', 'fa-cube'),
('C.9', 'Payroll Process', '/payroll-proc', 'fa-gears'),
('D', 'Loan', 'Loan', 'fa-paperclip'),
('D.1', 'Loan Transaction', '/loan', 'fa-cube'),
('E', 'Medical', 'Medical', ' fa-wheelchair'),
('E.1', 'Medical Type', '/medical-type', 'fa-wrench'),
('E.2', 'Medical Transaction', '/medical-income', 'fa-cube'),
('Y', 'Master Data', 'Master', 'fa-archive'),
('Y.1', 'Bank', '/bank', 'fa-bank'),
('Y.2', 'Company', '/company', 'fa-tachometer'),
('Y.3', 'User', '/user', 'fa-users'),
('Y.4', 'User Role', '/user-role', 'fa-users'),
('Y.5', 'Tax Location', '/tax-location', 'fa-building-o'),
('Y.6', 'Division', '/personnel-division', 'fa-th-large'),
('Y.7', 'Department', '/personnel-department', 'fa-th'),
('Y.8', 'Position', '/personnel-position', 'fa-suitcase'),
('Z', 'Reporting', 'Reporting', 'fa-print'),
('Z.1', '1721-A1', '/report-pph', 'fa-file'),
('Z.2', 'Payslip', '/payslip', 'fa-binoculars'),
('Z.3', 'Tax Monthly', '/report-tax', 'fa-file');

-- --------------------------------------------------------

--
-- Table structure for table `lk_calendar`
--

DROP TABLE IF EXISTS `lk_calendar`;
CREATE TABLE `lk_calendar` (
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_calendar`
--

INSERT INTO `lk_calendar` (`date`) VALUES
('2016-01-01'),
('2016-01-02'),
('2016-01-03'),
('2016-01-04'),
('2016-01-05'),
('2016-01-06'),
('2016-01-07'),
('2016-01-08'),
('2016-01-09'),
('2016-01-10'),
('2016-01-11'),
('2016-01-12'),
('2016-01-13'),
('2016-01-14'),
('2016-01-15'),
('2016-01-16'),
('2016-01-17'),
('2016-01-18'),
('2016-01-19'),
('2016-01-20'),
('2016-01-21'),
('2016-01-22'),
('2016-01-23'),
('2016-01-24'),
('2016-01-25'),
('2016-01-26'),
('2016-01-27'),
('2016-01-28'),
('2016-01-29'),
('2016-01-30'),
('2016-01-31'),
('2016-02-01'),
('2016-02-02'),
('2016-02-03'),
('2016-02-04'),
('2016-02-05'),
('2016-02-06'),
('2016-02-07'),
('2016-02-08'),
('2016-02-09'),
('2016-02-10'),
('2016-02-11'),
('2016-02-12'),
('2016-02-13'),
('2016-02-14'),
('2016-02-15'),
('2016-02-16'),
('2016-02-17'),
('2016-02-18'),
('2016-02-19'),
('2016-02-20'),
('2016-02-21'),
('2016-02-22'),
('2016-02-23'),
('2016-02-24'),
('2016-02-25'),
('2016-02-26'),
('2016-02-27'),
('2016-02-28'),
('2016-02-29'),
('2016-03-01'),
('2016-03-02'),
('2016-03-03'),
('2016-03-04'),
('2016-03-05'),
('2016-03-06'),
('2016-03-07'),
('2016-03-08'),
('2016-03-09'),
('2016-03-10'),
('2016-03-11'),
('2016-03-12'),
('2016-03-13'),
('2016-03-14'),
('2016-03-15'),
('2016-03-16'),
('2016-03-17'),
('2016-03-18'),
('2016-03-19'),
('2016-03-20'),
('2016-03-21'),
('2016-03-22'),
('2016-03-23'),
('2016-03-24'),
('2016-03-25'),
('2016-03-26'),
('2016-03-27'),
('2016-03-28'),
('2016-03-29'),
('2016-03-30'),
('2016-03-31'),
('2016-04-01'),
('2016-04-02'),
('2016-04-03'),
('2016-04-04'),
('2016-04-05'),
('2016-04-06'),
('2016-04-07'),
('2016-04-08'),
('2016-04-09'),
('2016-04-10'),
('2016-04-11'),
('2016-04-12'),
('2016-04-13'),
('2016-04-14'),
('2016-04-15'),
('2016-04-16'),
('2016-04-17'),
('2016-04-18'),
('2016-04-19'),
('2016-04-20'),
('2016-04-21'),
('2016-04-22'),
('2016-04-23'),
('2016-04-24'),
('2016-04-25'),
('2016-04-26'),
('2016-04-27'),
('2016-04-28'),
('2016-04-29'),
('2016-04-30'),
('2016-05-01'),
('2016-05-02'),
('2016-05-03'),
('2016-05-04'),
('2016-05-05'),
('2016-05-06'),
('2016-05-07'),
('2016-05-08'),
('2016-05-09'),
('2016-05-10'),
('2016-05-11'),
('2016-05-12'),
('2016-05-13'),
('2016-05-14'),
('2016-05-15'),
('2016-05-16'),
('2016-05-17'),
('2016-05-18'),
('2016-05-19'),
('2016-05-20'),
('2016-05-21'),
('2016-05-22'),
('2016-05-23'),
('2016-05-24'),
('2016-05-25'),
('2016-05-26'),
('2016-05-27'),
('2016-05-28'),
('2016-05-29'),
('2016-05-30'),
('2016-05-31'),
('2016-06-01'),
('2016-06-02'),
('2016-06-03'),
('2016-06-04'),
('2016-06-05'),
('2016-06-06'),
('2016-06-07'),
('2016-06-08'),
('2016-06-09'),
('2016-06-10'),
('2016-06-11'),
('2016-06-12'),
('2016-06-13'),
('2016-06-14'),
('2016-06-15'),
('2016-06-16'),
('2016-06-17'),
('2016-06-18'),
('2016-06-19'),
('2016-06-20'),
('2016-06-21'),
('2016-06-22'),
('2016-06-23'),
('2016-06-24'),
('2016-06-25'),
('2016-06-26'),
('2016-06-27'),
('2016-06-28'),
('2016-06-29'),
('2016-06-30'),
('2016-07-01'),
('2016-07-02'),
('2016-07-03'),
('2016-07-04'),
('2016-07-05'),
('2016-07-06'),
('2016-07-07'),
('2016-07-08'),
('2016-07-09'),
('2016-07-10'),
('2016-07-11'),
('2016-07-12'),
('2016-07-13'),
('2016-07-14'),
('2016-07-15'),
('2016-07-16'),
('2016-07-17'),
('2016-07-18'),
('2016-07-19'),
('2016-07-20'),
('2016-07-21'),
('2016-07-22'),
('2016-07-23'),
('2016-07-24'),
('2016-07-25'),
('2016-07-26'),
('2016-07-27'),
('2016-07-28'),
('2016-07-29'),
('2016-07-30'),
('2016-07-31'),
('2016-08-01'),
('2016-08-02'),
('2016-08-03'),
('2016-08-04'),
('2016-08-05'),
('2016-08-06'),
('2016-08-07'),
('2016-08-08'),
('2016-08-09'),
('2016-08-10'),
('2016-08-11'),
('2016-08-12'),
('2016-08-13'),
('2016-08-14'),
('2016-08-15'),
('2016-08-16'),
('2016-08-17'),
('2016-08-18'),
('2016-08-19'),
('2016-08-20'),
('2016-08-21'),
('2016-08-22'),
('2016-08-23'),
('2016-08-24'),
('2016-08-25'),
('2016-08-26'),
('2016-08-27'),
('2016-08-28'),
('2016-08-29'),
('2016-08-30'),
('2016-08-31'),
('2016-09-01'),
('2016-09-02'),
('2016-09-03'),
('2016-09-04'),
('2016-09-05'),
('2016-09-06'),
('2016-09-07'),
('2016-09-08'),
('2016-09-09'),
('2016-09-10'),
('2016-09-11'),
('2016-09-12'),
('2016-09-13'),
('2016-09-14'),
('2016-09-15'),
('2016-09-16'),
('2016-09-17'),
('2016-09-18'),
('2016-09-19'),
('2016-09-20'),
('2016-09-21'),
('2016-09-22'),
('2016-09-23'),
('2016-09-24'),
('2016-09-25'),
('2016-09-26'),
('2016-09-27'),
('2016-09-28'),
('2016-09-29'),
('2016-09-30'),
('2016-10-01'),
('2016-10-02'),
('2016-10-03'),
('2016-10-04'),
('2016-10-05'),
('2016-10-06'),
('2016-10-07'),
('2016-10-08'),
('2016-10-09'),
('2016-10-10'),
('2016-10-11'),
('2016-10-12'),
('2016-10-13'),
('2016-10-14'),
('2016-10-15'),
('2016-10-16'),
('2016-10-17'),
('2016-10-18'),
('2016-10-19'),
('2016-10-20'),
('2016-10-21'),
('2016-10-22'),
('2016-10-23'),
('2016-10-24'),
('2016-10-25'),
('2016-10-26'),
('2016-10-27'),
('2016-10-28'),
('2016-10-29'),
('2016-10-30'),
('2016-10-31'),
('2016-11-01'),
('2016-11-02'),
('2016-11-03'),
('2016-11-04'),
('2016-11-05'),
('2016-11-06'),
('2016-11-07'),
('2016-11-08'),
('2016-11-09'),
('2016-11-10'),
('2016-11-11'),
('2016-11-12'),
('2016-11-13'),
('2016-11-14'),
('2016-11-15'),
('2016-11-16'),
('2016-11-17'),
('2016-11-18'),
('2016-11-19'),
('2016-11-20'),
('2016-11-21'),
('2016-11-22'),
('2016-11-23'),
('2016-11-24'),
('2016-11-25'),
('2016-11-26'),
('2016-11-27'),
('2016-11-28'),
('2016-11-29'),
('2016-11-30'),
('2016-12-01'),
('2016-12-02'),
('2016-12-03'),
('2016-12-04'),
('2016-12-05'),
('2016-12-06'),
('2016-12-07'),
('2016-12-08'),
('2016-12-09'),
('2016-12-10'),
('2016-12-11'),
('2016-12-12'),
('2016-12-13'),
('2016-12-14'),
('2016-12-15'),
('2016-12-16'),
('2016-12-17'),
('2016-12-18'),
('2016-12-19'),
('2016-12-20'),
('2016-12-21'),
('2016-12-22'),
('2016-12-23'),
('2016-12-24'),
('2016-12-25'),
('2016-12-26'),
('2016-12-27'),
('2016-12-28'),
('2016-12-29'),
('2016-12-30'),
('2016-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `lk_currency`
--

DROP TABLE IF EXISTS `lk_currency`;
CREATE TABLE `lk_currency` (
  `currencyID` varchar(5) NOT NULL,
  `currencyName` varchar(50) NOT NULL,
  `currencySign` varchar(3) NOT NULL,
  `rate` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_currency`
--

INSERT INTO `lk_currency` (`currencyID`, `currencyName`, `currencySign`, `rate`) VALUES
('AUD', 'Australian Dollar', 'AUD', '1.00'),
('GBP', 'British Pound', 'GBP', '1.00'),
('IDR', 'Indonesian Rupiah', 'IDR', '1.00'),
('JPY', 'Japanese Yen', 'JPY', '1.00'),
('MYR', 'Malaysian Ringgit', 'MYR', '1.00'),
('SGD', 'Singapore Dollar', 'SGD', '1.00'),
('USD', 'United States Dollar', 'USD', '13800.00');

-- --------------------------------------------------------

--
-- Table structure for table `lk_education`
--

DROP TABLE IF EXISTS `lk_education`;
CREATE TABLE `lk_education` (
  `educationId` int(11) NOT NULL,
  `educationDescription` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_education`
--

INSERT INTO `lk_education` (`educationId`, `educationDescription`) VALUES
(1, 'No Education'),
(2, 'Elementary School'),
(3, 'Midle School'),
(4, 'High School'),
(5, 'High School - Vocational'),
(6, 'Diploma'),
(7, 'College - Bachelor'),
(8, 'College - Doctorate'),
(9, 'College - Professor');

-- --------------------------------------------------------

--
-- Table structure for table `lk_filteraccess`
--

DROP TABLE IF EXISTS `lk_filteraccess`;
CREATE TABLE `lk_filteraccess` (
  `accessID` varchar(10) NOT NULL,
  `insertAcc` bit(1) NOT NULL,
  `updateAcc` bit(1) NOT NULL,
  `deleteAcc` bit(1) NOT NULL,
  `authorizeAcc` bit(1) NOT NULL,
  `viewAcc` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_filteraccess`
--

INSERT INTO `lk_filteraccess` (`accessID`, `insertAcc`, `updateAcc`, `deleteAcc`, `authorizeAcc`, `viewAcc`) VALUES
('A', b'1', b'1', b'1', b'1', b'1'),
('A.1', b'1', b'1', b'1', b'1', b'1'),
('A.2', b'1', b'1', b'1', b'1', b'1'),
('A.3', b'1', b'1', b'1', b'1', b'1'),
('A.4', b'1', b'1', b'1', b'1', b'1'),
('B', b'1', b'1', b'1', b'1', b'1'),
('B.1', b'1', b'1', b'1', b'1', b'1'),
('B.2', b'1', b'1', b'1', b'1', b'1'),
('B.3', b'1', b'1', b'1', b'1', b'1'),
('B.7', b'1', b'1', b'1', b'1', b'1'),
('B.8', b'1', b'1', b'1', b'1', b'1'),
('C', b'1', b'1', b'1', b'1', b'1'),
('C.1', b'1', b'1', b'1', b'1', b'1'),
('C.2', b'1', b'1', b'1', b'1', b'1'),
('C.3', b'1', b'1', b'1', b'1', b'1'),
('C.4', b'1', b'1', b'1', b'1', b'1'),
('C.5', b'1', b'1', b'1', b'1', b'1'),
('C.6', b'1', b'1', b'1', b'1', b'1'),
('C.7', b'1', b'1', b'1', b'1', b'1'),
('C.8', b'1', b'1', b'1', b'1', b'1'),
('C.9', b'1', b'1', b'1', b'1', b'1'),
('D', b'1', b'1', b'1', b'1', b'1'),
('D.1', b'1', b'1', b'1', b'1', b'1'),
('E', b'1', b'1', b'1', b'1', b'1'),
('E.1', b'1', b'1', b'1', b'1', b'1'),
('E.2', b'1', b'1', b'1', b'1', b'1'),
('Y', b'1', b'1', b'1', b'1', b'1'),
('Y.1', b'1', b'1', b'1', b'1', b'1'),
('Y.2', b'1', b'1', b'1', b'1', b'1'),
('Y.3', b'1', b'1', b'1', b'1', b'1'),
('Y.4', b'1', b'1', b'1', b'1', b'1'),
('Y.5', b'1', b'1', b'1', b'1', b'1'),
('Y.6', b'1', b'1', b'1', b'1', b'1'),
('Y.7', b'1', b'1', b'1', b'1', b'1'),
('Y.8', b'1', b'1', b'1', b'1', b'1'),
('Z', b'1', b'1', b'1', b'1', b'1'),
('Z.1', b'1', b'1', b'1', b'1', b'1'),
('Z.2', b'1', b'1', b'1', b'1', b'1'),
('Z.3', b'1', b'1', b'1', b'1', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `lk_gender`
--

DROP TABLE IF EXISTS `lk_gender`;
CREATE TABLE `lk_gender` (
  `id` int(11) NOT NULL,
  `description` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_gender`
--

INSERT INTO `lk_gender` (`id`, `description`) VALUES
(1, 'MALE'),
(2, 'FEMALE');

-- --------------------------------------------------------

--
-- Table structure for table `lk_taxarticle`
--

DROP TABLE IF EXISTS `lk_taxarticle`;
CREATE TABLE `lk_taxarticle` (
  `articleId` varchar(50) NOT NULL,
  `articleDesc` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_taxarticle`
--

INSERT INTO `lk_taxarticle` (`articleId`, `articleDesc`) VALUES
('Article01', 'GAJI/PENSIUN ATAU THT/JHT'),
('Article02', 'TUNJANGAN PPh'),
('Article03', 'TUNJANGAN LAINNYA, UANG LEMBUR DAN SEBAGAINYA'),
('Article04', 'HONORARIUM DAN IMBALAN LAIN SEJENISNYA'),
('Article05', 'PREMI ASURANSI YANG DIBAYAR PEMBERI KERJA'),
('Article06', 'PENERIMAAN DALAM BENTUK NATURA DAN KENIKMATAN LAINNYA YANG DIKENAKAN PEMOTONGAN PPh PASAL 21'),
('Article07', 'TANTIEM, BONUS, GRATIFIKASI, JASA PRODUKSI DAN THR');

-- --------------------------------------------------------

--
-- Table structure for table `lk_time`
--

DROP TABLE IF EXISTS `lk_time`;
CREATE TABLE `lk_time` (
  `timeID` int(11) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `unitValue` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_time`
--

INSERT INTO `lk_time` (`timeID`, `unit`, `unitValue`) VALUES
(1, 'Hour', '1.00'),
(2, 'Day', '8.00'),
(3, 'Week', '40.00'),
(4, 'Month', '168.00');

-- --------------------------------------------------------

--
-- Table structure for table `lk_topupamount`
--

DROP TABLE IF EXISTS `lk_topupamount`;
CREATE TABLE `lk_topupamount` (
  `topupAmountID` int(11) NOT NULL,
  `amount` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_topupamount`
--

INSERT INTO `lk_topupamount` (`topupAmountID`, `amount`) VALUES
(1, '25000.00'),
(2, '50000.00'),
(3, '100000.00'),
(4, '200000.00'),
(5, '300000.00'),
(6, '500000.00'),
(7, '1000000.00'),
(8, '2000000.00');

-- --------------------------------------------------------

--
-- Table structure for table `lk_userrole`
--

DROP TABLE IF EXISTS `lk_userrole`;
CREATE TABLE `lk_userrole` (
  `userRoleID` int(11) NOT NULL,
  `userRole` varchar(100) NOT NULL DEFAULT '',
  `flagActive` bit(1) NOT NULL,
  `createdBy` varchar(50) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(50) NOT NULL,
  `editedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lk_userrole`
--

INSERT INTO `lk_userrole` (`userRoleID`, `userRole`, `flagActive`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
(1, 'ADMIN', b'1', 'SYSTEM', '2016-01-01 00:00:00', 'admin', '2016-11-16 08:31:32'),
(8, 'HRD', b'0', 'admin', '2016-03-23 14:35:02', '', '0000-00-00 00:00:00'),
(36, 'Asas', b'0', 'admin', '2016-03-24 13:21:37', '', '0000-00-00 00:00:00'),
(37, 'SUPERVISOR', b'0', 'admin', '2016-03-24 13:23:22', '', '0000-00-00 00:00:00'),
(38, 'SUPERTADMIN', b'0', 'admin', '2016-03-24 13:27:23', '', '0000-00-00 00:00:00'),
(40, 'HO', b'0', 'admin', '2016-03-24 13:35:05', '', '0000-00-00 00:00:00'),
(48, 'HNB', b'0', 'admin', '2016-03-24 16:29:21', 'admin', '2016-03-24 16:44:25'),
(49, 'ADM', b'0', 'admin', '2016-03-24 16:44:53', 'admin', '2016-03-24 16:50:51'),
(50, 'SUPERVISOR', b'1', 'admin', '2016-03-24 17:11:45', 'admin', '2016-03-28 09:31:50');

-- --------------------------------------------------------

--
-- Table structure for table `ms_alert`
--

DROP TABLE IF EXISTS `ms_alert`;
CREATE TABLE `ms_alert` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `query` varchar(9999) DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_alert`
--

INSERT INTO `ms_alert` (`id`, `title`, `query`, `flagActive`) VALUES
(1, 'A', 'SELECT ''A'' as FullName,''A'' As Title, ''A'' as ''Description''', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_attendanceholiday`
--

DROP TABLE IF EXISTS `ms_attendanceholiday`;
CREATE TABLE `ms_attendanceholiday` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `holidayDescription` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_attendanceholiday`
--

INSERT INTO `ms_attendanceholiday` (`id`, `date`, `holidayDescription`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
(1, '2016-12-03', 'ANCOL', 'admin', '2016-09-21 15:30:25', 'admin', '2016-09-21 15:37:56', b'1'),
(2, '2016-09-16', 'WUANNCOLLL', 'admin', '2016-09-21 15:38:11', 'admin', '2016-09-23 10:00:42', b'1'),
(3, '2016-09-02', '22222', 'admin', '2016-09-21 15:42:24', 'admin', '2016-09-21 15:42:24', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_attendanceovertime`
--

DROP TABLE IF EXISTS `ms_attendanceovertime`;
CREATE TABLE `ms_attendanceovertime` (
  `overtimeId` varchar(20) NOT NULL,
  `rate1` decimal(18,2) DEFAULT NULL,
  `rate2` decimal(18,2) DEFAULT NULL,
  `rate3` decimal(18,2) DEFAULT NULL,
  `rate4` decimal(18,2) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_attendanceovertime`
--

INSERT INTO `ms_attendanceovertime` (`overtimeId`, `rate1`, `rate2`, `rate3`, `rate4`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('DEPNAKER HOLIDAY', '2.00', '2.00', '2.00', '2.00', NULL, NULL, NULL, NULL),
('DEPNAKER WORKDAY', '1.50', '2.00', '2.00', '2.00', NULL, NULL, 'admin', '2016-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `ms_attendanceshift`
--

DROP TABLE IF EXISTS `ms_attendanceshift`;
CREATE TABLE `ms_attendanceshift` (
  `shitCode` varchar(40) NOT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `overnight` smallint(6) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_attendanceshift`
--

INSERT INTO `ms_attendanceshift` (`shitCode`, `start`, `end`, `overnight`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
('SHIFT1', '09:00:00', '18:00:00', 1, 'admin', '2016-06-08 09:20:08', 'admin', '2016-10-12 09:33:38', b'1'),
('SHIFT5', '08:23:00', '08:23:00', 1, 'admin', '2016-09-02 08:23:27', 'admin', '2016-10-12 09:33:45', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_attendancewcalcactualdetail`
--

DROP TABLE IF EXISTS `ms_attendancewcalcactualdetail`;
CREATE TABLE `ms_attendancewcalcactualdetail` (
  `id` varchar(20) DEFAULT NULL,
  `period` varchar(45) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `inTime` time DEFAULT NULL,
  `outTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_attendancewcalcactualdetail`
--

INSERT INTO `ms_attendancewcalcactualdetail` (`id`, `period`, `date`, `nik`, `inTime`, `outTime`) VALUES
('2016/01-1', '2016/01', '2016-01-28', '1', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ms_attendancewcalcactualhead`
--

DROP TABLE IF EXISTS `ms_attendancewcalcactualhead`;
CREATE TABLE `ms_attendancewcalcactualhead` (
  `id` varchar(20) NOT NULL,
  `period` varchar(45) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_attendancewcalcactualhead`
--

INSERT INTO `ms_attendancewcalcactualhead` (`id`, `period`, `nik`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('2016/01-1', '2016/01', '1', 'admin', '2016-12-09', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ms_attendancewcalcdet`
--

DROP TABLE IF EXISTS `ms_attendancewcalcdet`;
CREATE TABLE `ms_attendancewcalcdet` (
  `id` varchar(20) DEFAULT NULL,
  `period` varchar(15) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `shiftCode` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ms_attendancewcalchead`
--

DROP TABLE IF EXISTS `ms_attendancewcalchead`;
CREATE TABLE `ms_attendancewcalchead` (
  `id` varchar(20) NOT NULL,
  `period` varchar(11) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ms_bank`
--

DROP TABLE IF EXISTS `ms_bank`;
CREATE TABLE `ms_bank` (
  `bankId` varchar(50) NOT NULL,
  `bankDesc` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_bank`
--

INSERT INTO `ms_bank` (`bankId`, `bankDesc`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
('BCA', 'BANK CENTRAL ASIA', 'admin', '2016-08-23 14:28:13', 'admin', '2016-09-01 13:49:03', b'1'),
('BNI', 'BANK NASIONAL INDONESIA', 'admin', '2016-09-02 08:23:56', NULL, NULL, b'1'),
('BNIS', 'BNI SYARIAH', 'admin', '2017-02-01 09:50:06', NULL, NULL, b'1'),
('BRI', 'BANK RAKYAT INDONESIA', 'admin', '2016-08-12 13:51:54', NULL, NULL, b'1'),
('CIMB', 'CIMB NIAGA', 'admin', '2017-02-01 09:49:26', NULL, NULL, b'1'),
('IDX', 'BANK INDEX', 'admin', '2016-12-05 15:05:03', NULL, NULL, b'1'),
('MAS', 'BANK MAS', 'admin', '2016-10-06 11:17:12', NULL, NULL, b'1'),
('NOBU', 'BANK NOBU', 'admin', '2016-12-05 15:03:51', NULL, NULL, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_company`
--

DROP TABLE IF EXISTS `ms_company`;
CREATE TABLE `ms_company` (
  `companyID` int(11) NOT NULL,
  `companyName` varchar(100) NOT NULL,
  `companyAddress` varchar(20) DEFAULT NULL,
  `prorateSetting` varchar(20) DEFAULT NULL,
  `taxSetting` varchar(20) DEFAULT NULL,
  `startPayrollPeriod` varchar(20) DEFAULT NULL,
  `dateStart` int(11) DEFAULT NULL,
  `dateEnd` int(11) DEFAULT NULL,
  `overMonth` bit(1) DEFAULT NULL,
  `incHolidayDate` bit(1) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_company`
--

INSERT INTO `ms_company` (`companyID`, `companyName`, `companyAddress`, `prorateSetting`, `taxSetting`, `startPayrollPeriod`, `dateStart`, `dateEnd`, `overMonth`, `incHolidayDate`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
(1, 'EASYB', 'GADING SERPONG', 'W-DAY', '1', '2016/01', 1, 30, b'0', b'1', 'admin', NULL, 'admin', '2017-02-02 09:17:13');

-- --------------------------------------------------------

--
-- Table structure for table `ms_loan`
--

DROP TABLE IF EXISTS `ms_loan`;
CREATE TABLE `ms_loan` (
  `id` int(11) NOT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `registrationPeriod` varchar(10) DEFAULT NULL,
  `principal` decimal(18,2) DEFAULT NULL,
  `term` int(2) DEFAULT NULL,
  `downPayment` decimal(18,2) DEFAULT NULL,
  `principalPaid` decimal(18,2) DEFAULT NULL,
  `remarks` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_loan`
--

INSERT INTO `ms_loan` (`id`, `nik`, `registrationPeriod`, `principal`, `term`, `downPayment`, `principalPaid`, `remarks`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
(1, '1', '2017/01', '1000000.00', 5, '500000.00', '100000.00', '', 'admin', '2016-09-19 09:08:54', 'admin', '2016-09-21 10:39:00', b'0'),
(2, '1', '2016/01', '8000000.00', 8, '0.00', '1000000.00', '', 'admin', '2016-10-03 10:07:34', NULL, NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `ms_location`
--

DROP TABLE IF EXISTS `ms_location`;
CREATE TABLE `ms_location` (
  `locationID` int(11) NOT NULL,
  `locationCode` varchar(20) DEFAULT NULL,
  `locationName` varchar(50) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `flagActive` bit(1) NOT NULL,
  `createdBy` varchar(50) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_location`
--

INSERT INTO `ms_location` (`locationID`, `locationCode`, `locationName`, `address`, `phone`, `flagActive`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
(1, '', 'Tangerang', 'Jalan Boulevard Gading Serpong Blok B No. 8', '02187248572', b'1', 'SYSTEM', '2015-12-10 10:00:00', 'admin', '2015-12-10 10:39:07'),
(2, '', 'Jakarta', 'Jalan Boulevard Gading Serpong Blok B No. 8', '02187248572', b'1', 'SYSTEM', '2015-12-10 10:00:00', 'admin', '2015-12-10 10:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `ms_medicalincome`
--

DROP TABLE IF EXISTS `ms_medicalincome`;
CREATE TABLE `ms_medicalincome` (
  `id` int(11) NOT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `period` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_medicalincome`
--

INSERT INTO `ms_medicalincome` (`id`, `nik`, `period`, `amount`, `flagActive`) VALUES
(1, '1', '2016', '4.000.000,00', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_medicalincomedetail`
--

DROP TABLE IF EXISTS `ms_medicalincomedetail`;
CREATE TABLE `ms_medicalincomedetail` (
  `id` int(11) DEFAULT NULL,
  `claimDate` date DEFAULT NULL,
  `claimType` varchar(20) DEFAULT NULL,
  `inAmount` decimal(18,2) DEFAULT NULL,
  `outAmount` decimal(18,2) DEFAULT NULL,
  `notes` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` date DEFAULT NULL,
  `flagActive` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_medicalincomedetail`
--

INSERT INTO `ms_medicalincomedetail` (`id`, `claimDate`, `claimType`, `inAmount`, `outAmount`, `notes`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
(1, '2016-09-01', '4', '6000000.00', '0.00', '', NULL, NULL, 'admin', '2016-09-21', NULL),
(1, '2016-09-02', '2', '0.00', '2000000.00', 'Siloam Hospital', NULL, NULL, 'admin', '2016-09-21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ms_medicaltype`
--

DROP TABLE IF EXISTS `ms_medicaltype`;
CREATE TABLE `ms_medicaltype` (
  `id` int(11) NOT NULL,
  `typeDescription` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_medicaltype`
--

INSERT INTO `ms_medicaltype` (`id`, `typeDescription`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
(1, 'Opening Balance', 'admin', '2016-09-20 08:33:20', NULL, NULL, b'1'),
(2, 'Rawat Inap', 'admin', '2016-09-19 12:23:22', NULL, NULL, b'1'),
(3, 'Rawat Jalan', 'admin', '2016-09-19 12:23:29', NULL, NULL, b'1'),
(4, 'Gigi Umum', 'admin', '2016-09-29 11:29:12', 'admin', '2016-09-29 11:29:21', b'1'),
(5, 'Gigi Khusus', 'admin', '2016-09-29 11:29:28', NULL, NULL, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollcomponent`
--

DROP TABLE IF EXISTS `ms_payrollcomponent`;
CREATE TABLE `ms_payrollcomponent` (
  `payrollCode` varchar(20) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `parameter` varchar(45) DEFAULT NULL,
  `payrollDesc` varchar(45) DEFAULT NULL,
  `formula` varchar(45) DEFAULT NULL,
  `articleId` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollcomponent`
--

INSERT INTO `ms_payrollcomponent` (`payrollCode`, `type`, `parameter`, `payrollDesc`, `formula`, `articleId`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
('A01', '1', '2', 'SALARY', '', 'Article01', 'admin', '2016-06-14 09:22:54', NULL, NULL, b'1'),
('A02', '1', '2', 'TRANSPORTASI', '', 'Article01', 'admin', '2016-06-15 10:17:40', 'admin', '2016-08-25 10:48:05', b'1'),
('A03', '1', '2', 'UANG MAKAN', '', 'Article01', 'admin', '2016-06-15 10:23:44', 'admin', '2016-09-21 09:22:08', b'1'),
('A04', '1', '2', 'UANG DRIVER', '', '', 'admin', '2016-06-15 10:33:52', 'admin', '2016-09-21 11:25:01', b'1'),
('B02', '2', '2', 'TUNJANGAN HARI RAYA', '', 'Article07', 'admin', '2016-06-15 13:14:36', 'admin', '2016-09-22 13:59:05', b'1'),
('D01', '2', '1', 'HUTANG', '', NULL, 'admin', '2016-06-14 09:23:21', 'admin', '2016-06-14 09:23:41', b'1'),
('JHTCom', '3', '1', 'JHT Company', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1'),
('JHTEmp', '3', '1', 'JHT Employee', '', 'Article10', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1'),
('JKKCom', '3', '1', 'JKK Company', '', 'Article05', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1'),
('JKKEmp', '3', '1', 'JKK Employee', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1'),
('JKMCom', '3', '1', 'JKM Company', '', 'Article05', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1'),
('JKMEmp', '3', '1', 'JKM Employee', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1'),
('JPKCom', '3', '1', 'JPK Company', '', 'Article05', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1'),
('JPKEmp', '3', '1', 'JPK Employee', '', 'Article10', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1'),
('JPNCom', '3', '1', 'JPN Company', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1'),
('JPNEmp', '3', '1', 'JPN Employee', '', '', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollfix`
--

DROP TABLE IF EXISTS `ms_payrollfix`;
CREATE TABLE `ms_payrollfix` (
  `nik` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollfix`
--

INSERT INTO `ms_payrollfix` (`nik`) VALUES
('1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollfixdetail`
--

DROP TABLE IF EXISTS `ms_payrollfixdetail`;
CREATE TABLE `ms_payrollfixdetail` (
  `nik` int(11) DEFAULT NULL,
  `payrollCode` varchar(10) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollfixdetail`
--

INSERT INTO `ms_payrollfixdetail` (`nik`, `payrollCode`, `amount`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
(1, 'A01', '2000000.00', 'admin', '2016-06-17 08:14:51', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollfunctionalexpenses`
--

DROP TABLE IF EXISTS `ms_payrollfunctionalexpenses`;
CREATE TABLE `ms_payrollfunctionalexpenses` (
  `id` int(11) NOT NULL,
  `rate` decimal(18,2) DEFAULT NULL,
  `maxAmount` decimal(18,2) DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollfunctionalexpenses`
--

INSERT INTO `ms_payrollfunctionalexpenses` (`id`, `rate`, `maxAmount`, `editedBy`, `editedDate`) VALUES
(1, '6.00', '6000000.00', 'admin', '2016-09-27');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollincome`
--

DROP TABLE IF EXISTS `ms_payrollincome`;
CREATE TABLE `ms_payrollincome` (
  `nik` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollincome`
--

INSERT INTO `ms_payrollincome` (`nik`) VALUES
('1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollincomedetail`
--

DROP TABLE IF EXISTS `ms_payrollincomedetail`;
CREATE TABLE `ms_payrollincomedetail` (
  `nik` int(11) DEFAULT NULL,
  `payrollCode` varchar(45) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollincomedetail`
--

INSERT INTO `ms_payrollincomedetail` (`nik`, `payrollCode`, `amount`, `startDate`, `endDate`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
(1, 'A01', '20000000.00', '2016-01-01', '2019-01-01', 'admin', '2016-11-02', NULL, NULL, b'0'),
(1, 'A01', '30000000.00', '2016-01-01', '2017-12-31', 'admin', '2016-11-30', NULL, NULL, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrolljamsostek`
--

DROP TABLE IF EXISTS `ms_payrolljamsostek`;
CREATE TABLE `ms_payrolljamsostek` (
  `jamsostekCode` varchar(50) NOT NULL,
  `payrollCodeSource` varchar(20) DEFAULT NULL,
  `jkkCom` decimal(18,2) DEFAULT NULL,
  `jkkEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJkk` decimal(18,2) DEFAULT NULL,
  `jkmCom` decimal(18,2) DEFAULT NULL,
  `jkmEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJkm` decimal(18,2) DEFAULT NULL,
  `jhtCom` decimal(18,2) DEFAULT NULL,
  `jhtEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJht` decimal(18,2) DEFAULT NULL,
  `jpkCom` decimal(18,2) DEFAULT NULL,
  `jpkEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJpk` decimal(18,2) DEFAULT NULL,
  `jpnCom` decimal(18,2) DEFAULT NULL,
  `jpnEmp` decimal(18,2) DEFAULT NULL,
  `maxRateJpn` decimal(18,2) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrolljamsostek`
--

INSERT INTO `ms_payrolljamsostek` (`jamsostekCode`, `payrollCodeSource`, `jkkCom`, `jkkEmp`, `maxRateJkk`, `jkmCom`, `jkmEmp`, `maxRateJkm`, `jhtCom`, `jhtEmp`, `maxRateJht`, `jpkCom`, `jpkEmp`, `maxRateJpk`, `jpnCom`, `jpnEmp`, `maxRateJpn`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
('J01', 'A01', '240.00', '0.00', '9000.00', '300.00', '0.00', '9000.00', '370.00', '200.00', '9000.00', '400.00', '100.00', '9000.00', '200.00', '100.00', '9000.00', 'admin', '2016-11-11 14:52:51', 'admin', '2016-10-10 11:07:35', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollnonfix`
--

DROP TABLE IF EXISTS `ms_payrollnonfix`;
CREATE TABLE `ms_payrollnonfix` (
  `nik` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollnonfix`
--

INSERT INTO `ms_payrollnonfix` (`nik`) VALUES
('1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollnonfixdetail`
--

DROP TABLE IF EXISTS `ms_payrollnonfixdetail`;
CREATE TABLE `ms_payrollnonfixdetail` (
  `nik` varchar(20) DEFAULT NULL,
  `period` varchar(45) DEFAULT NULL,
  `payrollCode` varchar(45) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollnonfixdetail`
--

INSERT INTO `ms_payrollnonfixdetail` (`nik`, `period`, `payrollCode`, `amount`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('1', '2016/06', 'B02', '3000000.00', 'admin', '2016-06-20 08:44:11', NULL, NULL),
('1', '2016/07', 'D01', '8000000.00', 'admin', '2016-06-20 08:44:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollprorate`
--

DROP TABLE IF EXISTS `ms_payrollprorate`;
CREATE TABLE `ms_payrollprorate` (
  `prorateId` varchar(50) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `day` varchar(50) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollprorate`
--

INSERT INTO `ms_payrollprorate` (`prorateId`, `type`, `day`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('CD-01', '3', '', 'admin', '2016-06-16 15:32:52', 'admin', '2016-06-20 14:57:53'),
('FD-001', '1', '2', 'admin', NULL, NULL, NULL),
('W-DAY', '2', '', 'admin', '2016-06-16 15:33:01', 'admin', '2016-06-20 14:58:05');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollptkp`
--

DROP TABLE IF EXISTS `ms_payrollptkp`;
CREATE TABLE `ms_payrollptkp` (
  `id` int(11) NOT NULL,
  `ptkp` decimal(18,2) DEFAULT NULL,
  `rate` decimal(18,2) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrollptkp`
--

INSERT INTO `ms_payrollptkp` (`id`, `ptkp`, `rate`, `editedDate`, `editedBy`) VALUES
(1, '54000000.00', '4500000.00', '2016-09-01 15:30:10', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrollsetting`
--

DROP TABLE IF EXISTS `ms_payrollsetting`;
CREATE TABLE `ms_payrollsetting` (
  `Id` int(11) NOT NULL,
  `companyName` varchar(20) DEFAULT NULL,
  `companyAddress` varchar(20) DEFAULT NULL,
  `prorateSetting` varchar(20) DEFAULT NULL,
  `taxSetting` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrolltaxbefore`
--

DROP TABLE IF EXISTS `ms_payrolltaxbefore`;
CREATE TABLE `ms_payrolltaxbefore` (
  `id` varchar(45) NOT NULL,
  `nik` int(11) DEFAULT NULL,
  `year` varchar(45) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrolltaxbeforedetail`
--

DROP TABLE IF EXISTS `ms_payrolltaxbeforedetail`;
CREATE TABLE `ms_payrolltaxbeforedetail` (
  `id` varchar(20) DEFAULT NULL,
  `nomor` varchar(20) DEFAULT NULL,
  `periodStart` date DEFAULT NULL,
  `periodEnd` date DEFAULT NULL,
  `npwpCompany` varchar(45) DEFAULT NULL,
  `company` varchar(45) DEFAULT NULL,
  `netto` decimal(18,2) DEFAULT NULL,
  `taxPaid` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ms_payrolltaxrate`
--

DROP TABLE IF EXISTS `ms_payrolltaxrate`;
CREATE TABLE `ms_payrolltaxrate` (
  `tieringCode` varchar(10) NOT NULL,
  `start` decimal(18,2) DEFAULT NULL,
  `end` decimal(18,2) DEFAULT NULL,
  `npwpRate` decimal(18,2) DEFAULT NULL,
  `nonNpwpRate` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_payrolltaxrate`
--

INSERT INTO `ms_payrolltaxrate` (`tieringCode`, `start`, `end`, `npwpRate`, `nonNpwpRate`) VALUES
('T1', '0.00', '50000000.00', '5.00', '6.00'),
('T2', '50000000.00', '200000000.00', '15.00', '18.00'),
('T3', '200000000.00', '250000000.00', '25.00', '30.00'),
('T4', '250000000.00', '999999999.00', '30.00', '36.00');

-- --------------------------------------------------------

--
-- Table structure for table `ms_personnelcontract`
--

DROP TABLE IF EXISTS `ms_personnelcontract`;
CREATE TABLE `ms_personnelcontract` (
  `nik` int(11) DEFAULT NULL,
  `startWorking` date DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `docNo` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_personnelcontract`
--

INSERT INTO `ms_personnelcontract` (`nik`, `startWorking`, `startDate`, `endDate`, `docNo`, `status`, `position`) VALUES
(1, '2016-04-01', '2016-04-01', '2017-03-31', 'HR/2016/01-02', '5', '2'),
(1, '2017-04-01', '2017-04-01', '2018-03-31', 'HR/2016/01-03', '5', '8');

-- --------------------------------------------------------

--
-- Table structure for table `ms_personneldepartment`
--

DROP TABLE IF EXISTS `ms_personneldepartment`;
CREATE TABLE `ms_personneldepartment` (
  `departmentCode` int(11) NOT NULL,
  `departmentDesc` varchar(50) DEFAULT NULL,
  `divisionId` varchar(50) DEFAULT NULL,
  `shiftParm` varchar(20) DEFAULT NULL,
  `prorateSetting` varchar(20) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_personneldepartment`
--

INSERT INTO `ms_personneldepartment` (`departmentCode`, `departmentDesc`, `divisionId`, `shiftParm`, `prorateSetting`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
(1, 'ACCOUNT PAYABLE', '2', 'Shift 1', 'W-DAY', 'admin', '2016-09-07 10:36:41', 'admin', '2016-10-06 14:49:42', b'0'),
(2, 'WEB DEVELOPER', '1', 'SHIFT1', 'CD-01', 'admin', '2016-08-12 13:51:27', 'admin', '2016-10-12 15:36:00', b'1'),
(12, 'QA', '1', NULL, 'CD-01', 'admin', '2016-11-25 09:05:12', NULL, NULL, b'1'),
(13, 'QC', '1', NULL, 'CD-01', 'admin', '2016-11-25 09:05:57', NULL, NULL, b'1'),
(14, 'QD', '1', NULL, 'CD-01', 'admin', '2016-11-25 09:07:17', NULL, NULL, b'1'),
(15, 'QM', '1', NULL, 'CD-01', 'admin', '2016-11-25 09:07:27', NULL, NULL, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_personneldivision`
--

DROP TABLE IF EXISTS `ms_personneldivision`;
CREATE TABLE `ms_personneldivision` (
  `divisionId` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_personneldivision`
--

INSERT INTO `ms_personneldivision` (`divisionId`, `description`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
(1, 'DEVELOPMENT', 'admin', '2016-08-12 13:50:43', 'admin', '2016-11-14 14:47:59', b'1'),
(2, 'FINANCE & ACCOUNTING', 'admin', '2016-08-15 15:26:43', 'admin', '2016-10-06 14:49:58', b'1'),
(3, 'TRAINING', 'admin', '2016-10-06 11:20:32', NULL, NULL, b'0'),
(4, 'PURCHASING', 'admin', '2016-11-07 11:52:00', NULL, NULL, b'1'),
(6, 'TAX', 'admin', '2016-11-14 14:21:35', NULL, NULL, b'1'),
(7, 'PURCHASING', 'admin', '2016-11-14 14:56:01', NULL, NULL, b'1'),
(9, 'BANKING', 'admin', '2016-11-15 09:08:53', NULL, NULL, b'1'),
(10, 'NO DIVISION', 'admin', '2016-11-16 08:33:57', NULL, NULL, b'1'),
(11, 'IT SUPPORT', 'admin', '2016-11-24 10:14:20', NULL, NULL, b'1'),
(12, 'GENERAL AFFAIR', 'admin', '2016-11-24 10:29:50', NULL, NULL, b'1'),
(13, 'AS', 'admin', '2017-06-20 09:52:27', NULL, NULL, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_personnelfamily`
--

DROP TABLE IF EXISTS `ms_personnelfamily`;
CREATE TABLE `ms_personnelfamily` (
  `id` int(11) DEFAULT NULL,
  `firstName` varchar(30) DEFAULT NULL,
  `lastName` varchar(30) DEFAULT NULL,
  `relationship` varchar(20) DEFAULT NULL,
  `idNumber` varchar(20) DEFAULT NULL,
  `birthPlace` varchar(25) DEFAULT NULL,
  `birthDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_personnelfamily`
--

INSERT INTO `ms_personnelfamily` (`id`, `firstName`, `lastName`, `relationship`, `idNumber`, `birthPlace`, `birthDate`) VALUES
(1, 'ONCOM', 'KUDA', 'CHILD', '221414124', 'JAKARTA', '2016-10-31'),
(1, 'ONCOM', 'MARONCOM', 'WIFE', '211515', 'JAKARDAH', '2016-10-13');

-- --------------------------------------------------------

--
-- Table structure for table `ms_personnelhead`
--

DROP TABLE IF EXISTS `ms_personnelhead`;
CREATE TABLE `ms_personnelhead` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `fullName` varchar(50) DEFAULT NULL,
  `birthPlace` varchar(50) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `phoneNo` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `education` varchar(45) DEFAULT NULL,
  `major` varchar(50) DEFAULT NULL,
  `maritalStatus` varchar(20) DEFAULT NULL,
  `dependent` varchar(2) DEFAULT NULL,
  `empStatus` varchar(30) DEFAULT NULL,
  `jamsostekParm` varchar(30) DEFAULT NULL,
  `divisionId` varchar(45) DEFAULT NULL,
  `departmentId` varchar(45) DEFAULT NULL,
  `npwpNo` varchar(25) DEFAULT NULL,
  `bpjskNo` varchar(25) DEFAULT NULL,
  `bpkstkNo` varchar(25) DEFAULT NULL,
  `paymentMethod` varchar(10) DEFAULT NULL,
  `bankName` varchar(25) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `bankNo` varchar(25) DEFAULT NULL,
  `curency` varchar(8) DEFAULT NULL,
  `swiftCode` varchar(50) DEFAULT NULL,
  `ecFirstName` varchar(45) DEFAULT NULL,
  `ecLastName` varchar(45) DEFAULT NULL,
  `ecRelationShip` varchar(45) DEFAULT NULL,
  `ecPhone1` varchar(45) DEFAULT NULL,
  `ecPhone2` varchar(45) DEFAULT NULL,
  `npwpName` varchar(45) DEFAULT NULL,
  `npwpAddress` varchar(100) DEFAULT NULL,
  `taxId` int(11) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL,
  `imageKTP` varchar(200) DEFAULT NULL,
  `imagePhoto` varchar(200) DEFAULT NULL,
  `imageNPWP` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_personnelhead`
--

INSERT INTO `ms_personnelhead` (`id`, `firstName`, `lastName`, `fullName`, `birthPlace`, `birthDate`, `address`, `city`, `phoneNo`, `email`, `gender`, `education`, `major`, `maritalStatus`, `dependent`, `empStatus`, `jamsostekParm`, `divisionId`, `departmentId`, `npwpNo`, `bpjskNo`, `bpkstkNo`, `paymentMethod`, `bankName`, `branch`, `bankNo`, `curency`, `swiftCode`, `ecFirstName`, `ecLastName`, `ecRelationShip`, `ecPhone1`, `ecPhone2`, `npwpName`, `npwpAddress`, `taxId`, `nationality`, `country`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`, `imageKTP`, `imagePhoto`, `imageNPWP`) VALUES
(1, 'CHARLIE CUPU', 'CAESAR SETIONO', 'CHARLIE CUPU CAESAR SETIONO', 'BOGOR', '2016-11-01', 'Gama 17 No 14 Rt.002 Rw.008 Kec. Karawaci', 'TANGERANG', '+62-877-71161657', 'CHARLIE_EVOLUTION15@YAHOO.COM', '1', '7', 'INFORMATION SYSTEM', '1', '1', '1', 'J01', '1', '2', '22.222.222.2-222.222', '12345678', '12345678', '2', 'BNI', 'KC TANGERANG', '7788281882', 'IDR', '', 'RENY MARTINI', 'KARDIMAN', 'MOTHER', '+62-877-71161657', '', 'CHARLIE CAESAR SETIONO', 'Gama 17 No 14 Rt.002 Rw.008 Kec. Karawaci Tangerang', NULL, '1', 'INDONESIA', 'admin', '2017-02-13 10:39:58', NULL, NULL, b'1', 'keVcgsowicYG6LaPAbmuswyH8d6IPLoQ.jpg', '4JcMx7F9EwrtXRXzEpBSY4UCuuoe7s63.jpg', '_ntAYy7DODk11upqrY9NB5V6ueQDHUWV.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ms_personnelposition`
--

DROP TABLE IF EXISTS `ms_personnelposition`;
CREATE TABLE `ms_personnelposition` (
  `id` int(11) NOT NULL,
  `positionDescription` varchar(100) DEFAULT NULL,
  `jobDescription` varchar(1000) DEFAULT NULL,
  `createdBy` varchar(45) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(45) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_personnelposition`
--

INSERT INTO `ms_personnelposition` (`id`, `positionDescription`, `jobDescription`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
(1, 'DEVELOPER', 'MENGANALISA, MENYUSUN DAN MENDOKUMENTASIKAN RANGKAIAN KODE PROGRAM (CODING) BERDASARKAN TECHNICAL SPECIFICATION DOCUMENT YANG SUDAH DIBUAT SEBELUMNYA UNTUK DI-DELIVER DAN TERCAPAINYA KEBUTUHAN CUSTOMER.', 'admin', '2016-11-14 14:13:54', 'admin', '2016-11-14 14:13:54', b'1'),
(2, 'SYSTEM ENGINEER', '', 'admin', '2016-11-15 13:41:16', NULL, NULL, b'1'),
(6, 'SENIOR', '', 'admin', '2016-11-22 10:54:23', NULL, NULL, b'1'),
(7, 'JUNIOR', '', 'admin', '2016-11-22 10:56:59', NULL, NULL, b'1'),
(8, 'ADMIN', '', 'admin', '2016-11-22 10:58:23', NULL, NULL, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_setting`
--

DROP TABLE IF EXISTS `ms_setting`;
CREATE TABLE `ms_setting` (
  `key1` varchar(100) NOT NULL,
  `key2` varchar(100) DEFAULT NULL,
  `value1` varchar(100) DEFAULT NULL,
  `value2` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_setting`
--

INSERT INTO `ms_setting` (`key1`, `key2`, `value1`, `value2`) VALUES
('create', 'USER', '100000', NULL),
('job', 'YEAR', '1920', NULL),
('PayrollParm', 'DEDUCTION', '1', '1'),
('PayrollParm', 'ALLOWANCE', '2', '1'),
('PayrollType', 'FIX', '1', '1'),
('PayrollType', 'NON FIX', '2', '1'),
('ProrateParm', 'FIX DAY', '1', ''),
('ProrateParm', 'WORKING DAY', '2', ''),
('ProrateParm', 'CALENDAR DAY', '3', ''),
('TaxParm', 'GROSS', '1', '1'),
('TaxParm', 'NETT', '2', '2'),
('TaxParm', 'GROSS UP', '3', '3'),
('MaritalStatus', 'MARRIED', '1', ''),
('MaritalStatus', 'SINGLE', '2', ''),
('Nationality', 'LOCAL', '1', ''),
('Nationality', 'FOREIGNER', '2', ''),
('paymentMethod', 'CASH', '1', '0'),
('paymentMethod', 'TRANSFER', '2', '0'),
('Status', 'INTERNSHIP', '1', ''),
('Status', 'OUTSOURCE', '2', ''),
('Status', 'CONTRACT', '3', ''),
('Status', 'PROBATION', '4', ''),
('Status', 'PERMANENT', '5', ''),
('Status', 'PIECE WORKER', '6', ''),
('PayrollType', 'FORMULA', '4', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ms_taxlocation`
--

DROP TABLE IF EXISTS `ms_taxlocation`;
CREATE TABLE `ms_taxlocation` (
  `id` varchar(50) NOT NULL,
  `npwpNo` varchar(50) DEFAULT NULL,
  `officeName` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `zipCode` varchar(45) DEFAULT NULL,
  `phone1` varchar(50) DEFAULT NULL,
  `phone2` varchar(50) DEFAULT NULL,
  `taxSigner_1` varchar(50) DEFAULT NULL,
  `position_1` varchar(50) DEFAULT NULL,
  `npwpSigner_1` varchar(50) DEFAULT NULL,
  `phone1_1` varchar(50) DEFAULT NULL,
  `phone2_1` varchar(45) DEFAULT NULL,
  `email_1` varchar(50) DEFAULT NULL,
  `taxSigner_2` varchar(50) DEFAULT NULL,
  `position_2` varchar(50) DEFAULT NULL,
  `npwpSigner_2` varchar(50) DEFAULT NULL,
  `phone1_2` varchar(50) DEFAULT NULL,
  `phone2_2` varchar(50) DEFAULT NULL,
  `email_2` varchar(45) DEFAULT NULL,
  `taxSigner_3` varchar(45) DEFAULT NULL,
  `position_3` varchar(45) DEFAULT NULL,
  `npwpSigner_3` varchar(45) DEFAULT NULL,
  `phone1_3` varchar(45) DEFAULT NULL,
  `phone2_3` varchar(45) DEFAULT NULL,
  `email_3` varchar(45) DEFAULT NULL,
  `createdBy` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `flagActive` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_taxlocation`
--

INSERT INTO `ms_taxlocation` (`id`, `npwpNo`, `officeName`, `address`, `city`, `zipCode`, `phone1`, `phone2`, `taxSigner_1`, `position_1`, `npwpSigner_1`, `phone1_1`, `phone2_1`, `email_1`, `taxSigner_2`, `position_2`, `npwpSigner_2`, `phone1_2`, `phone2_2`, `email_2`, `taxSigner_3`, `position_3`, `npwpSigner_3`, `phone1_3`, `phone2_3`, `email_3`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `flagActive`) VALUES
('2', '1000100100100', 'KPP Tangerang', '', 'Tangerang', NULL, NULL, NULL, 'Charlie Setiono', NULL, '1000100100100', 'charlie_evolution15@yahoo.com', NULL, '087771161657', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', '2016-06-07 10:57:05', 'admin', '2016-06-10 16:15:11', b'0'),
('KPP01', '00.000.000.0-000.000', 'KPP KARAWACI', 'ADDRESS', 'CITY', '15114', '+00-000-00000000000', '+00-000-00000000000', 'NAME1', 'POSITION1', '11.111.111.1-111.111', '+11-111-11111111111', '+11-111-11111111111', 'EMAIL1', 'NAME2', 'POSITION2', '22.222.222.2-222.222', '+22-222-22222222222', '+22-222-22222222222', 'EMAIL2', 'NAME3', 'POSITION3', '33.333.333.3-333.333', '+33-333-33333333333', '+33-333-33333333333', 'EMAIL3', 'admin', '2016-10-06 16:52:35', 'admin', '2016-10-06 09:30:11', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `ms_user`
--

DROP TABLE IF EXISTS `ms_user`;
CREATE TABLE `ms_user` (
  `username` varchar(50) NOT NULL DEFAULT '',
  `fullName` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `salt` varchar(45) NOT NULL,
  `userRoleID` int(11) NOT NULL DEFAULT '0',
  `locationID` int(11) NOT NULL,
  `dbName` varchar(100) DEFAULT NULL,
  `companyID` int(11) DEFAULT NULL,
  `flagActive` bit(1) NOT NULL,
  `createdBy` varchar(50) NOT NULL DEFAULT '0',
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(50) NOT NULL,
  `editedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_user`
--

INSERT INTO `ms_user` (`username`, `fullName`, `password`, `salt`, `userRoleID`, `locationID`, `dbName`, `companyID`, `flagActive`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('adaDAD', 'adadaD', '168c6db6268254a208825c61c5390b43', 'YXk2SL9LgGxPJukzlaCP1PgbF__f3I0X3CJzoFrK_2VGM', 1, 1, 'adaDAD', 1, b'1', 'admin', '2016-04-29 11:03:54', '', '0000-00-00 00:00:00'),
('admin', 'Administrator', 'deb384376e3da17fb354a0b697a89c51', 'QpZ25sb8Vn-B3nDOY2WuvO8s9-Okm9Hk19cqW5OyXWU6v', 1, 1, 'easyb_web', 1, b'1', 'admin', '2015-08-04 12:21:49', 'admin', '2016-01-18 09:05:03'),
('BABEH', 'REYAN', '575e4b6b8aab6b17eeb139e2af6d772c', 'UZ_P82vFwn_vVG7SXsQ6HSKq8oqoal-e2wDbF_WrTd3eU', 1, 1, 'PT_KARYA_DIGITAL', 1, b'1', 'admin', '2016-07-26 08:42:49', '', '0000-00-00 00:00:00'),
('tommy', 'tommy', 'aaed332822db358ae0bf9cba48734f1e', 'z7oVAh35OBoeoUvjXgz6OjYyyJte_66btveMFKlfEMwXt', 1, 1, 'tommy', 1, b'1', 'admin', '2016-06-30 14:07:13', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ms_useraccess`
--

DROP TABLE IF EXISTS `ms_useraccess`;
CREATE TABLE `ms_useraccess` (
  `ID` int(11) NOT NULL,
  `userRoleID` int(11) NOT NULL,
  `accessID` varchar(10) NOT NULL,
  `indexAcc` bit(1) NOT NULL,
  `viewAcc` bit(1) NOT NULL,
  `insertAcc` bit(1) NOT NULL,
  `updateAcc` bit(1) NOT NULL,
  `deleteAcc` bit(1) NOT NULL,
  `authorizeAcc` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ms_useraccess`
--

INSERT INTO `ms_useraccess` (`ID`, `userRoleID`, `accessID`, `indexAcc`, `viewAcc`, `insertAcc`, `updateAcc`, `deleteAcc`, `authorizeAcc`) VALUES
(316, 1, 'Z.1', b'1', b'1', b'1', b'1', b'1', b'1'),
(317, 1, 'B', b'0', b'0', b'0', b'0', b'0', b'0'),
(318, 1, 'Y.1', b'1', b'1', b'1', b'1', b'1', b'1'),
(319, 1, 'Y.2', b'1', b'1', b'1', b'1', b'1', b'1'),
(320, 1, 'Y.7', b'1', b'1', b'1', b'1', b'1', b'1'),
(321, 1, 'Y.6', b'1', b'1', b'1', b'1', b'1', b'1'),
(322, 1, 'C.6', b'1', b'1', b'1', b'1', b'1', b'1'),
(323, 1, 'B.3', b'1', b'1', b'1', b'1', b'1', b'1'),
(324, 1, 'C.7', b'1', b'1', b'1', b'1', b'1', b'1'),
(325, 1, 'C.8', b'1', b'1', b'1', b'1', b'1', b'1'),
(326, 1, 'C.4', b'1', b'1', b'1', b'1', b'1', b'1'),
(327, 1, 'D', b'0', b'0', b'0', b'0', b'0', b'0'),
(328, 1, 'D.1', b'1', b'1', b'1', b'1', b'1', b'1'),
(329, 1, 'Y', b'0', b'0', b'0', b'0', b'0', b'0'),
(330, 1, 'E', b'0', b'0', b'0', b'0', b'0', b'0'),
(331, 1, 'E.2', b'1', b'1', b'1', b'1', b'1', b'1'),
(332, 1, 'E.1', b'1', b'1', b'1', b'1', b'1', b'1'),
(333, 1, 'B.2', b'1', b'1', b'1', b'1', b'1', b'1'),
(334, 1, 'C', b'0', b'0', b'0', b'0', b'0', b'0'),
(335, 1, 'C.1', b'1', b'1', b'1', b'1', b'1', b'1'),
(336, 1, 'C.9', b'1', b'1', b'1', b'1', b'1', b'1'),
(337, 1, 'Z.2', b'1', b'1', b'1', b'1', b'1', b'1'),
(338, 1, 'A', b'0', b'0', b'0', b'0', b'0', b'0'),
(339, 1, 'Y.8', b'1', b'1', b'1', b'1', b'1', b'1'),
(340, 1, 'A.1', b'1', b'1', b'1', b'1', b'1', b'1'),
(341, 1, 'C.5', b'1', b'1', b'1', b'1', b'1', b'1'),
(342, 1, 'C.3', b'1', b'1', b'1', b'1', b'1', b'1'),
(343, 1, 'Z', b'0', b'0', b'0', b'0', b'0', b'0'),
(344, 1, 'B.1', b'1', b'1', b'1', b'1', b'1', b'1'),
(345, 1, 'Y.5', b'1', b'1', b'1', b'1', b'1', b'1'),
(346, 1, 'Z.3', b'1', b'1', b'1', b'1', b'1', b'1'),
(347, 1, 'C.2', b'1', b'1', b'1', b'1', b'1', b'1'),
(348, 1, 'Y.3', b'1', b'1', b'1', b'1', b'1', b'1'),
(349, 1, 'Y.4', b'1', b'1', b'1', b'1', b'1', b'1'),
(350, 1, 'B.7', b'1', b'1', b'1', b'1', b'1', b'1'),
(351, 1, 'B.8', b'1', b'1', b'1', b'1', b'1', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `tr_companybalance`
--

DROP TABLE IF EXISTS `tr_companybalance`;
CREATE TABLE `tr_companybalance` (
  `ID` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `balanceDate` datetime NOT NULL,
  `amount` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tr_confirmationtopup`
--

DROP TABLE IF EXISTS `tr_confirmationtopup`;
CREATE TABLE `tr_confirmationtopup` (
  `confirmationID` int(11) NOT NULL,
  `confirmationDate` datetime NOT NULL,
  `topupID` int(11) NOT NULL,
  `methodID` int(11) NOT NULL,
  `bankAccount` varchar(50) NOT NULL,
  `bankName` varchar(50) NOT NULL,
  `accountName` varchar(50) NOT NULL,
  `subTotal` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tr_loanproc`
--

DROP TABLE IF EXISTS `tr_loanproc`;
CREATE TABLE `tr_loanproc` (
  `id` int(11) NOT NULL,
  `paymentPeriod` varchar(20) DEFAULT NULL,
  `principalPaid` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tr_payroll`
--

DROP TABLE IF EXISTS `tr_payroll`;
CREATE TABLE `tr_payroll` (
  `period` varchar(20) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `payrollCode` varchar(45) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_payroll`
--

INSERT INTO `tr_payroll` (`period`, `nik`, `payrollCode`, `amount`) VALUES
('2016/04', '1', 'A01', '30000000.00'),
('2016/04', '1', 'JKKCom', '21600.00'),
('2016/04', '1', 'JKKEmp', '0.00'),
('2016/04', '1', 'JKMCom', '27000.00'),
('2016/04', '1', 'JKMEmp', '0.00'),
('2016/04', '1', 'JHTCom', '33300.00'),
('2016/04', '1', 'JHTEmp', '18000.00'),
('2016/04', '1', 'JPKCom', '36000.00'),
('2016/04', '1', 'JPKEmp', '9000.00'),
('2016/04', '1', 'JPNCom', '18000.00'),
('2016/04', '1', 'JPNEmp', '9000.00');

-- --------------------------------------------------------

--
-- Table structure for table `tr_payrollproc`
--

DROP TABLE IF EXISTS `tr_payrollproc`;
CREATE TABLE `tr_payrollproc` (
  `period` varchar(45) NOT NULL,
  `status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_payrollproc`
--

INSERT INTO `tr_payrollproc` (`period`, `status`) VALUES
('2016/01', 'PROCESS'),
('2016/02', 'PROCESS'),
('2016/03', 'PROCESS'),
('2016/04', 'PROCESS'),
('2016/05', 'PROCESS');

-- --------------------------------------------------------

--
-- Table structure for table `tr_payrolltaxfinalproc`
--

DROP TABLE IF EXISTS `tr_payrolltaxfinalproc`;
CREATE TABLE `tr_payrolltaxfinalproc` (
  `period` varchar(8) DEFAULT NULL,
  `sequance` varchar(45) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `npwp` int(11) DEFAULT NULL,
  `T01` decimal(18,2) DEFAULT NULL,
  `T02` decimal(18,2) DEFAULT NULL,
  `T03` decimal(18,2) DEFAULT NULL,
  `T04` decimal(18,2) DEFAULT NULL,
  `T05` decimal(18,2) DEFAULT NULL,
  `T06` decimal(18,2) DEFAULT NULL,
  `T07` decimal(18,2) DEFAULT NULL,
  `biayaJabatan` decimal(18,2) DEFAULT NULL,
  `T10` decimal(18,2) DEFAULT NULL,
  `prevNetto` decimal(18,2) DEFAULT NULL,
  `prevNettoBJ` decimal(18,2) DEFAULT NULL,
  `netto` decimal(18,2) DEFAULT NULL,
  `nettoBJ` varchar(45) DEFAULT NULL,
  `nettoSum` decimal(18,2) DEFAULT NULL,
  `nettoSumBJ` varchar(45) DEFAULT NULL,
  `ptkp` decimal(18,2) DEFAULT NULL,
  `pkp` decimal(18,2) DEFAULT NULL,
  `pkp1` decimal(18,2) DEFAULT NULL,
  `pkp2` decimal(18,2) DEFAULT NULL,
  `pkp3` decimal(18,2) DEFAULT NULL,
  `pkp4` decimal(18,2) DEFAULT NULL,
  `prevIncome` decimal(18,2) DEFAULT NULL,
  `prevTaxPaid` decimal(18,2) DEFAULT NULL,
  `workmonth` int(11) DEFAULT NULL,
  `pphCalc` decimal(18,2) DEFAULT NULL,
  `pphAmount` decimal(18,2) DEFAULT NULL,
  `isFinal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tr_payrolltaxincome`
--

DROP TABLE IF EXISTS `tr_payrolltaxincome`;
CREATE TABLE `tr_payrolltaxincome` (
  `period` varchar(8) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `dependent` varchar(45) DEFAULT NULL,
  `T01` decimal(18,2) DEFAULT NULL,
  `T03` decimal(18,2) DEFAULT NULL,
  `T04` decimal(18,2) DEFAULT NULL,
  `T05` decimal(18,2) DEFAULT NULL,
  `T06` decimal(18,2) DEFAULT NULL,
  `T07` decimal(18,2) DEFAULT NULL,
  `T10` decimal(18,2) DEFAULT NULL,
  `NettoBefore` decimal(18,2) DEFAULT NULL,
  `PPhBefore` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_payrolltaxincome`
--

INSERT INTO `tr_payrolltaxincome` (`period`, `nik`, `dependent`, `T01`, `T03`, `T04`, `T05`, `T06`, `T07`, `T10`, `NettoBefore`, `PPhBefore`) VALUES
('2016/04', '1', '1', '30000000.00', '0.00', '0.00', '84600.00', '0.00', '0.00', '27000.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `tr_payrolltaxmonthlyproc`
--

DROP TABLE IF EXISTS `tr_payrolltaxmonthlyproc`;
CREATE TABLE `tr_payrolltaxmonthlyproc` (
  `period` varchar(8) DEFAULT NULL,
  `sequance` varchar(45) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `npwp` int(11) DEFAULT NULL,
  `T01` decimal(18,2) DEFAULT NULL,
  `T02` decimal(18,2) DEFAULT NULL,
  `T03` decimal(18,2) DEFAULT NULL,
  `T04` decimal(18,2) DEFAULT NULL,
  `T05` decimal(18,2) DEFAULT NULL,
  `T06` decimal(18,2) DEFAULT NULL,
  `T07` decimal(18,2) DEFAULT NULL,
  `biayaJabatan` decimal(18,2) DEFAULT NULL,
  `T10` decimal(18,2) DEFAULT NULL,
  `prevNetto` decimal(18,2) DEFAULT NULL,
  `prevNettoBJ` decimal(18,2) DEFAULT NULL,
  `netto` decimal(18,2) DEFAULT NULL,
  `nettoBJ` varchar(45) DEFAULT NULL,
  `nettoSum` decimal(18,2) DEFAULT NULL,
  `nettoSumBJ` varchar(45) DEFAULT NULL,
  `ptkp` decimal(18,2) DEFAULT NULL,
  `pkp` decimal(18,2) DEFAULT NULL,
  `pkp1` decimal(18,2) DEFAULT NULL,
  `pkp2` decimal(18,2) DEFAULT NULL,
  `pkp3` decimal(18,2) DEFAULT NULL,
  `pkp4` decimal(18,2) DEFAULT NULL,
  `prevIncome` decimal(18,2) DEFAULT NULL,
  `prevTaxPaid` decimal(18,2) DEFAULT NULL,
  `workmonth` int(11) DEFAULT NULL,
  `pphCalc` decimal(18,2) DEFAULT NULL,
  `pphAmount` decimal(18,2) DEFAULT NULL,
  `isFinal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_payrolltaxmonthlyproc`
--

INSERT INTO `tr_payrolltaxmonthlyproc` (`period`, `sequance`, `nik`, `npwp`, `T01`, `T02`, `T03`, `T04`, `T05`, `T06`, `T07`, `biayaJabatan`, `T10`, `prevNetto`, `prevNettoBJ`, `netto`, `nettoBJ`, `nettoSum`, `nettoSumBJ`, `ptkp`, `pkp`, `pkp1`, `pkp2`, `pkp3`, `pkp4`, `prevIncome`, `prevTaxPaid`, `workmonth`, `pphCalc`, `pphAmount`, `isFinal`) VALUES
('2016/04', '0', '1', 1, '30000000.00', '0.00', '0.00', '0.00', '84600.00', '0.00', '0.00', '4500000.00', '27000.00', '0.00', '0.00', '30057600.00', '30084600', '270518400.00', '270761400', '58500000.00', '207518000.00', '50000000.00', '157518000.00', '0.00', '0.00', '0.00', '0.00', 9, '26127700.00', '2903077.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tr_payrolltaxmonthlyprocdummy`
--

DROP TABLE IF EXISTS `tr_payrolltaxmonthlyprocdummy`;
CREATE TABLE `tr_payrolltaxmonthlyprocdummy` (
  `period` varchar(8) DEFAULT NULL,
  `sequance` varchar(45) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `npwp` int(11) DEFAULT NULL,
  `T01` decimal(18,2) DEFAULT NULL,
  `T02` decimal(18,2) DEFAULT NULL,
  `T03` decimal(18,2) DEFAULT NULL,
  `T04` decimal(18,2) DEFAULT NULL,
  `T05` decimal(18,2) DEFAULT NULL,
  `T06` decimal(18,2) DEFAULT NULL,
  `T07` decimal(18,2) DEFAULT NULL,
  `biayaJabatan` decimal(18,2) DEFAULT NULL,
  `T10` decimal(18,2) DEFAULT NULL,
  `prevNetto` decimal(18,2) DEFAULT NULL,
  `prevNettoBJ` decimal(18,2) DEFAULT NULL,
  `netto` decimal(18,2) DEFAULT NULL,
  `nettoBJ` varchar(45) DEFAULT NULL,
  `nettoSum` decimal(18,2) DEFAULT NULL,
  `nettoSumBJ` varchar(45) DEFAULT NULL,
  `ptkp` decimal(18,2) DEFAULT NULL,
  `pkp` decimal(18,2) DEFAULT NULL,
  `pkp1` decimal(18,2) DEFAULT NULL,
  `pkp2` decimal(18,2) DEFAULT NULL,
  `pkp3` decimal(18,2) DEFAULT NULL,
  `pkp4` decimal(18,2) DEFAULT NULL,
  `prevIncome` decimal(18,2) DEFAULT NULL,
  `prevTaxPaid` decimal(18,2) DEFAULT NULL,
  `workmonth` int(11) DEFAULT NULL,
  `pphCalc` decimal(18,2) DEFAULT NULL,
  `pphAmount` decimal(18,2) DEFAULT NULL,
  `isFinal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tr_topup`
--

DROP TABLE IF EXISTS `tr_topup`;
CREATE TABLE `tr_topup` (
  `topupID` int(11) NOT NULL,
  `topupDate` datetime NOT NULL,
  `companyID` int(11) NOT NULL,
  `bankID` int(11) NOT NULL,
  `totalTopup` decimal(18,2) NOT NULL,
  `totalPayment` decimal(18,2) DEFAULT NULL,
  `additionalInfo` varchar(200) DEFAULT NULL,
  `createdBy` varchar(50) NOT NULL,
  `topupName` varchar(50) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(50) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `status` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_topup`
--

INSERT INTO `tr_topup` (`topupID`, `topupDate`, `companyID`, `bankID`, `totalTopup`, `totalPayment`, `additionalInfo`, `createdBy`, `topupName`, `createdDate`, `editedBy`, `editedDate`, `status`) VALUES
(1, '2016-06-28 00:00:00', 1, 1, '1000000.00', '1000000.00', '', 'admin', 'Administrator', '2016-06-28 08:59:13', 'admin', '2016-06-28 08:59:41', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `tr_transactionlog`
--

DROP TABLE IF EXISTS `tr_transactionlog`;
CREATE TABLE `tr_transactionlog` (
  `transactionLogID` int(11) NOT NULL,
  `transactionLogDate` datetime NOT NULL,
  `transactionLogDesc` varchar(100) NOT NULL,
  `refNum` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_transactionlog`
--

INSERT INTO `tr_transactionlog` (`transactionLogID`, `transactionLogDate`, `transactionLogDesc`, `refNum`, `username`) VALUES
(1, '2016-11-10 14:11:20', 'Running Payroll', '2016/02', 'admin'),
(2, '2016-11-10 14:12:14', 'Running Payroll', '2016/03', 'admin'),
(3, '2016-11-10 14:13:29', 'Running Payroll', '2016/01', 'admin'),
(4, '2016-11-10 14:13:43', 'Running Payroll', '2016/02', 'admin'),
(5, '2016-11-10 14:14:01', 'Running Payroll', '2016/03', 'admin'),
(6, '2016-11-10 14:14:20', 'Running Payroll', '2016/04', 'admin'),
(7, '2016-11-10 14:14:42', 'Running Payroll', '2016/05', 'admin'),
(8, '2016-11-10 14:17:19', 'Running Payroll', '2016/06', 'admin'),
(9, '2016-11-10 14:17:34', 'Running Payroll', '2016/07', 'admin'),
(10, '2016-11-10 16:13:47', 'Delete Master Bank', '2', 'admin'),
(11, '2016-11-10 16:16:19', 'Delete Master Tax Location', '2', 'admin'),
(12, '2016-11-10 16:17:52', 'Delete Master Tax Location', 'KPP01', 'admin'),
(13, '2016-11-11 14:52:38', 'Update Master Jamasostek', 'J01', 'admin'),
(14, '2016-11-11 14:52:51', 'Update Master Jamasostek', 'J01', 'admin'),
(15, '2016-11-11 16:55:34', 'Add Master Payroll Component', 'KOK', 'admin'),
(16, '2016-11-11 16:56:14', 'Edit Master Payroll Component', 'KOK', 'admin'),
(17, '2016-11-11 17:05:20', 'Edit Master Payroll Component', 'KOK', 'admin'),
(18, '2016-11-14 14:21:35', 'Add Master Division', 'TAX', 'admin'),
(19, '2016-11-15 13:41:16', 'Insert Master Position', 'SYSTEM ENGINEER', 'admin'),
(20, '2016-11-15 13:47:32', 'Insert Master Position', 'ADMIN', 'admin'),
(21, '2016-11-16 08:31:33', 'Edit Master User Role', 'ADMIN', 'admin'),
(22, '2016-11-22 10:51:15', 'Insert Master Position', 'JUNIOR1', 'admin'),
(23, '2016-11-22 10:52:55', 'Insert Master Position', 'JUNIOR', 'admin'),
(24, '2016-11-22 10:54:23', 'Insert Master Position', 'SENIOR', 'admin'),
(25, '2016-11-22 10:56:59', 'Insert Master Position', 'JUNIOR', 'admin'),
(26, '2016-11-22 10:58:23', 'Insert Master Position', 'ADMIN', 'admin'),
(27, '2016-11-24 10:49:25', 'Add Master Department', 'GUDANG', 'admin'),
(28, '2016-11-30 16:18:06', 'Running Payroll', '2016/01', 'admin'),
(29, '2016-11-30 16:18:12', 'Running Payroll', '2016/02', 'admin'),
(30, '2016-11-30 16:18:15', 'Running Payroll', '2016/03', 'admin'),
(31, '2016-11-30 16:18:23', 'Running Payroll', '2016/04', 'admin'),
(32, '2016-11-30 16:18:28', 'Running Payroll', '2016/05', 'admin'),
(33, '2016-11-30 16:46:39', 'Edit Income ', '1', 'admin'),
(34, '2016-11-30 16:46:50', 'Running Payroll', '2016/01', 'admin'),
(35, '2016-11-30 16:46:56', 'Running Payroll', '2016/02', 'admin'),
(36, '2016-11-30 16:46:59', 'Running Payroll', '2016/03', 'admin'),
(37, '2016-11-30 16:47:03', 'Running Payroll', '2016/04', 'admin'),
(38, '2016-12-05 15:03:53', 'Insert Master Bank', 'NOBU', 'admin'),
(39, '2016-12-05 15:05:03', 'Insert Master Bank', 'IDX', 'admin'),
(40, '2016-12-07 09:54:14', 'Edit Master Overtime', '1', 'admin'),
(41, '2016-12-07 09:54:24', 'Edit Master Overtime', 'DEPNAKER', 'admin'),
(42, '2016-12-07 09:54:57', 'Edit Master Overtime', 'DEPNAKER WORKDAY', 'admin'),
(43, '2016-12-07 09:55:08', 'Edit Master Overtime', 'DEPNAKER WORKDAY', 'admin'),
(44, '2016-12-07 09:55:31', 'Add Master Overtime', 'DEPNAKER HOLIDAY', 'admin'),
(45, '2016-12-09 13:49:14', 'Create Working Actual Schedule', '2016/01-1', 'admin'),
(46, '2017-02-01 09:49:26', 'Insert Master Bank', 'CIMB', 'admin'),
(47, '2017-02-01 09:50:06', 'Insert Master Bank', 'BNIS', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tr_working`
--

DROP TABLE IF EXISTS `tr_working`;
CREATE TABLE `tr_working` (
  `nik` varchar(10) DEFAULT NULL,
  `period` varchar(20) DEFAULT NULL,
  `Schedule` int(11) DEFAULT NULL,
  `Actual` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tr_workingtime`
--

DROP TABLE IF EXISTS `tr_workingtime`;
CREATE TABLE `tr_workingtime` (
  `nik` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `inTime` time DEFAULT NULL,
  `outTime` time DEFAULT NULL,
  `shiftCode` varchar(30) DEFAULT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `gapAct` time DEFAULT NULL,
  `gapSch` time DEFAULT NULL,
  `gap` time DEFAULT NULL,
  `OT1` float DEFAULT NULL,
  `OT2` float DEFAULT NULL,
  `OT3` float DEFAULT NULL,
  `OT4` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tr_workingtimecalc`
--

DROP TABLE IF EXISTS `tr_workingtimecalc`;
CREATE TABLE `tr_workingtimecalc` (
  `period` varchar(10) DEFAULT NULL,
  `nik` varchar(45) DEFAULT NULL,
  `OT1` float DEFAULT NULL,
  `OT2` float DEFAULT NULL,
  `OT3` float DEFAULT NULL,
  `OT4` float DEFAULT NULL,
  `Total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vr_crosstab`
--
DROP VIEW IF EXISTS `vr_crosstab`;
CREATE TABLE `vr_crosstab` (
`period` varchar(20)
,`NIK` varchar(45)
,`A01` decimal(32,2)
,`A02` decimal(32,2)
,`A03` decimal(32,2)
,`A04` decimal(32,2)
,`B02` decimal(32,2)
,`D01` decimal(32,2)
,`D02` decimal(32,2)
,`D03` decimal(32,2)
,`JHTCom` decimal(32,2)
,`JHTEmp` decimal(32,2)
,`JKKCom` decimal(32,2)
,`JKKEmp` decimal(32,2)
,`JKMCom` decimal(32,2)
,`JKMEmp` decimal(32,2)
,`JPKCom` decimal(32,2)
,`JPKEmp` decimal(32,2)
,`JPNCom` decimal(32,2)
,`JPNEmp` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Structure for view `vr_crosstab`
--
DROP TABLE IF EXISTS `vr_crosstab`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vr_crosstab`  AS  select `tr_payroll`.`period` AS `period`,`tr_payroll`.`nik` AS `NIK`,sum((case `tr_payroll`.`payrollCode` when 'A01' then `tr_payroll`.`amount` else 0 end)) AS `A01`,sum((case `tr_payroll`.`payrollCode` when 'A02' then `tr_payroll`.`amount` else 0 end)) AS `A02`,sum((case `tr_payroll`.`payrollCode` when 'A03' then `tr_payroll`.`amount` else 0 end)) AS `A03`,sum((case `tr_payroll`.`payrollCode` when 'A04' then `tr_payroll`.`amount` else 0 end)) AS `A04`,sum((case `tr_payroll`.`payrollCode` when 'B02' then `tr_payroll`.`amount` else 0 end)) AS `B02`,sum((case `tr_payroll`.`payrollCode` when 'D01' then `tr_payroll`.`amount` else 0 end)) AS `D01`,sum((case `tr_payroll`.`payrollCode` when 'D02' then `tr_payroll`.`amount` else 0 end)) AS `D02`,sum((case `tr_payroll`.`payrollCode` when 'D03' then `tr_payroll`.`amount` else 0 end)) AS `D03`,sum((case `tr_payroll`.`payrollCode` when 'JHTCom' then `tr_payroll`.`amount` else 0 end)) AS `JHTCom`,sum((case `tr_payroll`.`payrollCode` when 'JHTEmp' then `tr_payroll`.`amount` else 0 end)) AS `JHTEmp`,sum((case `tr_payroll`.`payrollCode` when 'JKKCom' then `tr_payroll`.`amount` else 0 end)) AS `JKKCom`,sum((case `tr_payroll`.`payrollCode` when 'JKKEmp' then `tr_payroll`.`amount` else 0 end)) AS `JKKEmp`,sum((case `tr_payroll`.`payrollCode` when 'JKMCom' then `tr_payroll`.`amount` else 0 end)) AS `JKMCom`,sum((case `tr_payroll`.`payrollCode` when 'JKMEmp' then `tr_payroll`.`amount` else 0 end)) AS `JKMEmp`,sum((case `tr_payroll`.`payrollCode` when 'JPKCom' then `tr_payroll`.`amount` else 0 end)) AS `JPKCom`,sum((case `tr_payroll`.`payrollCode` when 'JPKEmp' then `tr_payroll`.`amount` else 0 end)) AS `JPKEmp`,sum((case `tr_payroll`.`payrollCode` when 'JPNCom' then `tr_payroll`.`amount` else 0 end)) AS `JPNCom`,sum((case `tr_payroll`.`payrollCode` when 'JPNEmp' then `tr_payroll`.`amount` else 0 end)) AS `JPNEmp` from `tr_payroll` group by `tr_payroll`.`nik`,`tr_payroll`.`period` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lk_accesscontrol`
--
ALTER TABLE `lk_accesscontrol`
  ADD PRIMARY KEY (`accessID`);

--
-- Indexes for table `lk_calendar`
--
ALTER TABLE `lk_calendar`
  ADD PRIMARY KEY (`date`);

--
-- Indexes for table `lk_currency`
--
ALTER TABLE `lk_currency`
  ADD PRIMARY KEY (`currencyID`);

--
-- Indexes for table `lk_education`
--
ALTER TABLE `lk_education`
  ADD PRIMARY KEY (`educationId`);

--
-- Indexes for table `lk_filteraccess`
--
ALTER TABLE `lk_filteraccess`
  ADD PRIMARY KEY (`accessID`);

--
-- Indexes for table `lk_gender`
--
ALTER TABLE `lk_gender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lk_taxarticle`
--
ALTER TABLE `lk_taxarticle`
  ADD PRIMARY KEY (`articleId`);

--
-- Indexes for table `lk_time`
--
ALTER TABLE `lk_time`
  ADD PRIMARY KEY (`timeID`);

--
-- Indexes for table `lk_topupamount`
--
ALTER TABLE `lk_topupamount`
  ADD PRIMARY KEY (`topupAmountID`);

--
-- Indexes for table `lk_userrole`
--
ALTER TABLE `lk_userrole`
  ADD PRIMARY KEY (`userRoleID`);

--
-- Indexes for table `ms_alert`
--
ALTER TABLE `ms_alert`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_attendanceholiday`
--
ALTER TABLE `ms_attendanceholiday`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_attendanceovertime`
--
ALTER TABLE `ms_attendanceovertime`
  ADD PRIMARY KEY (`overtimeId`);

--
-- Indexes for table `ms_attendanceshift`
--
ALTER TABLE `ms_attendanceshift`
  ADD PRIMARY KEY (`shitCode`);

--
-- Indexes for table `ms_attendancewcalcactualhead`
--
ALTER TABLE `ms_attendancewcalcactualhead`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_attendancewcalchead`
--
ALTER TABLE `ms_attendancewcalchead`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_bank`
--
ALTER TABLE `ms_bank`
  ADD PRIMARY KEY (`bankId`);

--
-- Indexes for table `ms_company`
--
ALTER TABLE `ms_company`
  ADD PRIMARY KEY (`companyID`);

--
-- Indexes for table `ms_loan`
--
ALTER TABLE `ms_loan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_location`
--
ALTER TABLE `ms_location`
  ADD PRIMARY KEY (`locationID`);

--
-- Indexes for table `ms_medicalincome`
--
ALTER TABLE `ms_medicalincome`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_medicaltype`
--
ALTER TABLE `ms_medicaltype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_payrollcomponent`
--
ALTER TABLE `ms_payrollcomponent`
  ADD PRIMARY KEY (`payrollCode`);

--
-- Indexes for table `ms_payrollfix`
--
ALTER TABLE `ms_payrollfix`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `ms_payrollfunctionalexpenses`
--
ALTER TABLE `ms_payrollfunctionalexpenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_payrollincome`
--
ALTER TABLE `ms_payrollincome`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `ms_payrolljamsostek`
--
ALTER TABLE `ms_payrolljamsostek`
  ADD PRIMARY KEY (`jamsostekCode`);

--
-- Indexes for table `ms_payrollnonfix`
--
ALTER TABLE `ms_payrollnonfix`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `ms_payrollprorate`
--
ALTER TABLE `ms_payrollprorate`
  ADD PRIMARY KEY (`prorateId`);

--
-- Indexes for table `ms_payrollptkp`
--
ALTER TABLE `ms_payrollptkp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_payrollsetting`
--
ALTER TABLE `ms_payrollsetting`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `ms_payrolltaxbefore`
--
ALTER TABLE `ms_payrolltaxbefore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_payrolltaxrate`
--
ALTER TABLE `ms_payrolltaxrate`
  ADD PRIMARY KEY (`tieringCode`);

--
-- Indexes for table `ms_personneldepartment`
--
ALTER TABLE `ms_personneldepartment`
  ADD PRIMARY KEY (`departmentCode`);

--
-- Indexes for table `ms_personneldivision`
--
ALTER TABLE `ms_personneldivision`
  ADD PRIMARY KEY (`divisionId`);

--
-- Indexes for table `ms_personnelhead`
--
ALTER TABLE `ms_personnelhead`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_personnelposition`
--
ALTER TABLE `ms_personnelposition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_taxlocation`
--
ALTER TABLE `ms_taxlocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_user`
--
ALTER TABLE `ms_user`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `userRoleConstrain_idx` (`userRoleID`);

--
-- Indexes for table `ms_useraccess`
--
ALTER TABLE `ms_useraccess`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_useraccessfilter` (`accessID`);

--
-- Indexes for table `tr_companybalance`
--
ALTER TABLE `tr_companybalance`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tr_confirmationtopup`
--
ALTER TABLE `tr_confirmationtopup`
  ADD PRIMARY KEY (`confirmationID`);

--
-- Indexes for table `tr_payrollproc`
--
ALTER TABLE `tr_payrollproc`
  ADD PRIMARY KEY (`period`);

--
-- Indexes for table `tr_topup`
--
ALTER TABLE `tr_topup`
  ADD PRIMARY KEY (`topupID`);

--
-- Indexes for table `tr_transactionlog`
--
ALTER TABLE `tr_transactionlog`
  ADD PRIMARY KEY (`transactionLogID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lk_education`
--
ALTER TABLE `lk_education`
  MODIFY `educationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `lk_time`
--
ALTER TABLE `lk_time`
  MODIFY `timeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `lk_topupamount`
--
ALTER TABLE `lk_topupamount`
  MODIFY `topupAmountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `lk_userrole`
--
ALTER TABLE `lk_userrole`
  MODIFY `userRoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `ms_alert`
--
ALTER TABLE `ms_alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ms_attendanceholiday`
--
ALTER TABLE `ms_attendanceholiday`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ms_loan`
--
ALTER TABLE `ms_loan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ms_location`
--
ALTER TABLE `ms_location`
  MODIFY `locationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ms_medicalincome`
--
ALTER TABLE `ms_medicalincome`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ms_medicaltype`
--
ALTER TABLE `ms_medicaltype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ms_personneldepartment`
--
ALTER TABLE `ms_personneldepartment`
  MODIFY `departmentCode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `ms_personneldivision`
--
ALTER TABLE `ms_personneldivision`
  MODIFY `divisionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `ms_personnelhead`
--
ALTER TABLE `ms_personnelhead`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ms_personnelposition`
--
ALTER TABLE `ms_personnelposition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `ms_useraccess`
--
ALTER TABLE `ms_useraccess`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=352;
--
-- AUTO_INCREMENT for table `tr_companybalance`
--
ALTER TABLE `tr_companybalance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_confirmationtopup`
--
ALTER TABLE `tr_confirmationtopup`
  MODIFY `confirmationID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_topup`
--
ALTER TABLE `tr_topup`
  MODIFY `topupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tr_transactionlog`
--
ALTER TABLE `tr_transactionlog`
  MODIFY `transactionLogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
