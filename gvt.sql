-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2021 at 01:48 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gvt`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_ADD_QUESTION` (IN `action` VARCHAR(20), IN `test_id` INT, IN `quesTitle` VARCHAR(200), IN `option1` VARCHAR(150), IN `option2` VARCHAR(150), IN `option3` VARCHAR(150), IN `option4` VARCHAR(150), IN `correctOption` VARCHAR(150), IN `class` VARCHAR(50), IN `section` VARCHAR(50), IN `stream` VARCHAR(50), IN `subject` VARCHAR(100), IN `created_by` VARCHAR(20))  NO SQL
BEGIN	
DECLARE classid INT;
DECLARE secid INT;
DECLARE streamid INT;
DECLARE subid INT;

    IF action='addQuestion' THEN
    
    SET classid=(SELECT INT_CLASS_ID FROM m_class WHERE VCH_CLASS_NAME=class);
    SET secid=(SELECT INT_SEC_ID FROM m_section WHERE VCH_SECTION_NAME=section);
    SET streamid=(SELECT INT_STREAM_ID FROM m_stream WHERE VCH_STREAM=stream);
    SET subid=(SELECT INT_SUB_ID FROM m_subjects WHERE VCH_SUBJECT=subject);
    
	INSERT INTO m_test_questions (INT_TEST_ID,VCH_QUES_TITLE,VCH_OPTION_1,VCH_OPTION_2,VCH_OPTION_3,VCH_OPTION_4,VCH_CORRECT_OPTION,INT_FOR_CLASS_ID,INT_FOR_SECTION_ID,INT_STREAM_ID,INT_SUBJECT_ID,VCH_CREATED_BY) VALUES(test_id,quesTitle,option1,option2,option3,option4,correctOption,classid,secid,streamid,subid,created_by);
    
    SELECT 1 AS "msg";
    
END IF;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_ASSIGN_STU_QUESTIONS` (IN `action` VARCHAR(20), IN `testId` INT, IN `ques` INT, IN `stuId` INT)  NO SQL
BEGIN
DECLARE cnt INT;
DECLARE chk INT;
DECLARE attend INT;
IF action = 'totalQues' THEN
	SELECT COUNT(*) FROM m_test_questions WHERE INT_TEST_ID=testId;
END IF;

IF action = 'selectQuesForStu' THEN
	SET cnt = (SELECT COUNT(*) FROM m_test_questions WHERE INT_TEST_ID=testId);
    
    SET chk = (SELECT COUNT(*) FROM t_stu_attending_test WHERE INT_TEST_ID=testId AND INT_STU_ID=stuId);
    
  /* checking if questions for that student is already present or not */
    IF chk = cnt THEN	/* Already presen */
    	SELECT INT_QUESTION_ID FROM t_stu_attending_test WHERE INT_TEST_ID=testId AND INT_STU_ID=stuId;
    
    ELSE	/* Not present */
   
        /* subquery to insert multiple rows */
INSERT INTO t_stu_attending_test (INT_STU_ID,INT_TEST_ID,INT_CLASS_ID,	INT_SECTION_ID,INT_STREAM_ID,INT_QUESTION_ID,VCH_QUESTION_TITLE,VCH_OPTION_1,VCH_OPTION_2,VCH_OPTION_3,VCH_OPTION_4,VCH_CORRECT_ANSWER) 
SELECT stuId,INT_TEST_ID,INT_FOR_CLASS_ID,INT_FOR_SECTION_ID,INT_STREAM_ID,INT_QUES_ID,VCH_QUES_TITLE,VCH_OPTION_1,VCH_OPTION_2,VCH_OPTION_3,VCH_OPTION_4,VCH_CORRECT_OPTION FROM m_test_questions WHERE INT_TEST_ID=testId ORDER BY RAND() LIMIT cnt;

SELECT INT_QUESTION_ID FROM t_stu_attending_test WHERE INT_TEST_ID=testId AND INT_STU_ID=stuId;
    
    END IF;
    
END IF;

IF action = 'bindThisQuestion' THEN

	SET attend = (SELECT INT_IS_ATTENDED FROM t_stu_attending_test WHERE INT_TEST_ID=testId AND INT_QUESTION_ID=ques AND INT_STU_ID=stuId);

	IF attend>0 THEN	/* respond already submitted */
    	SELECT INT_QUESTION_ID, VCH_QUESTION_TITLE, VCH_OPTION_1, VCH_OPTION_2, VCH_OPTION_3, VCH_OPTION_4, VCH_CORRECT_ANSWER,VCH_STUDENT_ANSWER FROM t_stu_attending_test WHERE INT_TEST_ID=testId AND INT_QUESTION_ID=ques AND INT_STU_ID=stuId;
        
        ELSE		/* new request */
        
        SELECT INT_QUESTION_ID, VCH_QUESTION_TITLE, VCH_OPTION_1, VCH_OPTION_2, VCH_OPTION_3, VCH_OPTION_4, VCH_CORRECT_ANSWER,VCH_STUDENT_ANSWER FROM t_stu_attending_test WHERE INT_TEST_ID=testId AND INT_QUESTION_ID=ques AND INT_STU_ID=stuId;
        
    END IF;
    
END IF;	/* end of bindThisQuestion */

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_CHECK_TEST_ATTENDED` (IN `action` VARCHAR(20), IN `testId` INT, IN `stuId` INT, IN `stuClass` INT, IN `stuSection` INT, IN `stuStream` INT)  NO SQL
BEGIN
DECLARE chk INT;

IF action='testAttended' THEN
	SET chk=(SELECT COUNT(INT_IS_ATTENDED) FROM t_stu_attending_test WHERE INT_TEST_ID=testId AND INT_STU_ID=stuId AND INT_CLASS_ID=stuClass AND INT_SECTION_ID=stuSection AND INT_STREAM_ID=stuStream AND INT_IS_ATTENDED=1);
    
    IF chk>0 THEN
    	SELECT 1 AS msg;
    ELSE
    	SELECT 0 AS msg;
    END IF;
    
END IF;

	IF action='alreadyAttended' THEN
    	SELECT sat.INT_TEST_ID,mt.VCH_TEST_TITLE,mt.DTM_SCHEDULE_DATE,mt.TEST_START_TIME,mt.TEST_END_TIME FROM t_stu_attending_test sat INNER JOIN m_test mt ON sat.INT_TEST_ID=mt.INT_TEST_ID WHERE sat.INT_STU_ID=stuId AND sat.INT_CLASS_ID=stuClass AND sat.INT_SECTION_ID=stuSection AND sat.INT_STREAM_ID=stuStream AND sat.INT_IS_ATTENDED=1 GROUP BY sat.INT_TEST_ID;
    END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_CREATE_TEST` (IN `action` VARCHAR(20), IN `test_title` VARCHAR(120), IN `test_date` DATE, IN `test_endTime` TIME, IN `class_id` INT, IN `section_id` INT, IN `test_startTime` TIME, IN `stream_id` INT, IN `subject_id` INT, IN `creator_id` VARCHAR(20))  NO SQL
BEGIN

IF action = 'createTest' THEN
	INSERT INTO m_test (VCH_TEST_TITLE, INT_FOR_CLASS_ID, INT_FOR_SECTION, 	INT_SUBJECT_ID, INT_STREAM_ID, DTM_SCHEDULE_DATE,TEST_END_TIME, TEST_START_TIME, VCH_CREATOR_ID) VALUES (test_title, class_id, section_id, subject_id, stream_id, test_date, test_endTime, test_startTime, creator_id);
END IF;

SELECT 1 AS msg;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_FINISH_TEST` (IN `action` VARCHAR(20), IN `stuId` INT, IN `testId` INT)  NO SQL
BEGIN

IF action='finishTest' THEN
	UPDATE t_stu_attending_test SET INT_IS_ATTENDED=1 WHERE INT_STU_ID=stuId AND INT_TEST_ID=testId;
    
    SELECT 1 AS msg;
    
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_GET_ADMIN_TEA_TEST` (IN `action` VARCHAR(20), IN `creator` VARCHAR(20))  NO SQL
BEGIN

IF action='upcomingTest' THEN
	SELECT INT_TEST_ID, VCH_TEST_TITLE, TEST_START_TIME, TEST_END_TIME, DTM_SCHEDULE_DATE FROM m_test WHERE VCH_CREATOR_ID=creator AND INT_IS_TEST_ENDED=0 OR Date(DTM_SCHEDULE_DATE)>=CURDATE();
END IF;

IF action='creatorCompTest' THEN
	SELECT INT_TEST_ID, VCH_TEST_TITLE, TEST_START_TIME, TEST_END_TIME, DTM_SCHEDULE_DATE FROM m_test WHERE VCH_CREATOR_ID=creator AND INT_IS_TEST_ENDED=1 OR Date(DTM_SCHEDULE_DATE)<CURDATE();
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_GET_ALL_TEST` (IN `action` VARCHAR(20), IN `creator_id` VARCHAR(20), IN `test_id` INT)  NO SQL
BEGIN

IF action = 'getAllTest' THEN
	SELECT mt.INT_TEST_ID, mt.VCH_TEST_TITLE, mc.VCH_CLASS_NAME, ms.VCH_SECTION_NAME, msub.VCH_SUBJECT, mstr.VCH_STREAM, mt.DTM_SCHEDULE_DATE, mt.TEST_START_TIME, mt.TEST_END_TIME, mt.BIT_TEST_ASSIGN FROM m_test mt INNER JOIN m_class mc ON mt.INT_FOR_CLASS_ID=mc.INT_CLASS_ID INNER JOIN m_section ms ON mt.INT_FOR_SECTION=ms.INT_SEC_ID INNER JOIN m_stream mstr ON mt.INT_STREAM_ID = mstr.INT_STREAM_ID INNER JOIN m_subjects msub ON mt.INT_SUBJECT_ID=msub.INT_SUB_ID WHERE mt.VCH_CREATOR_ID = creator_id AND mt.BIT_DELETED_FLAG = 0;
END IF;

IF action = 'bindTestDetail' THEN
SELECT INT_TEST_ID, VCH_TEST_TITLE, INT_FOR_CLASS_ID, INT_FOR_SECTION, INT_SUBJECT_ID, INT_STREAM_ID, DTM_SCHEDULE_DATE, TEST_START_TIME, TEST_END_TIME, BIT_TEST_ASSIGN FROM m_test WHERE VCH_CREATOR_ID = creator_id AND INT_TEST_ID = test_id AND BIT_DELETED_FLAG = 0;
END IF;

IF action = 'loadTestDetails' THEN
	SELECT mt.INT_TEST_ID, mt.VCH_TEST_TITLE, mc.VCH_CLASS_NAME, ms.VCH_SECTION_NAME, msub.VCH_SUBJECT, mstr.VCH_STREAM, mt.DTM_SCHEDULE_DATE, mt.TEST_START_TIME, mt.TEST_END_TIME, mt.BIT_TEST_ASSIGN FROM m_test mt INNER JOIN m_class mc ON mt.INT_FOR_CLASS_ID=mc.INT_CLASS_ID INNER JOIN m_section ms ON mt.INT_FOR_SECTION=ms.INT_SEC_ID INNER JOIN m_stream mstr ON mt.INT_STREAM_ID = mstr.INT_STREAM_ID INNER JOIN m_subjects msub ON mt.INT_SUBJECT_ID=msub.INT_SUB_ID WHERE mt.VCH_CREATOR_ID = creator_id AND mt.INT_TEST_ID=test_id AND mt.BIT_DELETED_FLAG = 0;
END IF;

IF action='selectQuesForStu' THEN
	SELECT INT_QUES_ID FROM m_test_questions WHERE INT_TEST_ID=test_id ;
END IF;

IF action='getTestDetails' THEN
	SELECT mt.INT_TEST_ID, mt.VCH_TEST_TITLE, mc.VCH_CLASS_NAME, ms.VCH_SECTION_NAME, msub.VCH_SUBJECT, mstr.VCH_STREAM, mt.DTM_SCHEDULE_DATE, mt.TEST_START_TIME, mt.TEST_END_TIME, mt.BIT_TEST_ASSIGN FROM m_test mt INNER JOIN m_class mc ON mt.INT_FOR_CLASS_ID=mc.INT_CLASS_ID INNER JOIN m_section ms ON mt.INT_FOR_SECTION=ms.INT_SEC_ID INNER JOIN m_stream mstr ON mt.INT_STREAM_ID = mstr.INT_STREAM_ID INNER JOIN m_subjects msub ON mt.INT_SUBJECT_ID=msub.INT_SUB_ID WHERE mt.INT_TEST_ID=test_id AND mt.BIT_DELETED_FLAG = 0;
END IF;

IF action='endTest' THEN
	UPDATE m_test SET INT_IS_TEST_ENDED=1 WHERE INT_TEST_ID=test_id AND BIT_DELETED_FLAG=0;
END IF;

IF action='checkTestAvail' THEN
	SELECT DTM_SCHEDULE_DATE,TEST_START_TIME,TEST_END_TIME FROM m_test WHERE INT_TEST_ID=test_id AND INT_IS_TEST_ENDED=0 AND BIT_DELETED_FLAG=0;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_GET_DATA` (IN `action` VARCHAR(20), IN `c_id` INT)  NO SQL
BEGIN

IF action = 'getsubject' THEN
SELECT INT_SUB_ID, VCH_SUBJECT FROM m_subjects WHERE INT_CLASS_ID = c_id;
END IF;

IF action = 'loadstream' THEN
SELECT INT_STREAM_ID, VCH_STREAM FROM m_stream WHERE BIT_DELETED_FLAG = 0;
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_GET_TEA_DETAILS` (IN `action` VARCHAR(20), IN `tid` VARCHAR(20))  NO SQL
BEGIN

IF action='getTeaDetailToEdit' THEN
	SELECT m.VCH_TEACH_NAME,m.VCH_TEACH_EMAIL,m.VCH_UNI_ID,t.CLASS_1,t.CLASS_2,t.CLASS_3,t.CLASS_4,t.CLASS_5,t.CLASS_6,t.CLASS_7,t.CLASS_8,t.CLASS_9,t.CLASS_10,t.CLASS_11,t.CLASS_12 FROM m_teachers m inner join t_teacher_auth t on m.VCH_UNI_ID=t.VCH_UNI_ID WHERE m.VCH_USER_TYPE='teacher' AND m.VCH_UNI_ID=tid;
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_GET_USR_DETAILS` (IN `action` INT(20), IN `stuid` INT)  NO SQL
BEGIN

IF action='getStuDetailToEdit' THEN
    	SELECT INT_STU_ID,INT_ADMISSION_NO,INT_CLASS_ROLLNO,INT_CLASS_ID,INT_SECTION_ID,VCH_STU_NAME,VCH_STU_EMAIL FROM m_students WHERE 
INT_STU_ID=stuid AND BIT_DELETED_FLAG=0;
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_LOAD_EDIT_QUESTION` (IN `action` VARCHAR(20), IN `testId` INT, IN `creator` VARCHAR(20), IN `quesId` INT)  NO SQL
BEGIN

IF action='loadQuestions' THEN
	SELECT INT_QUES_ID, VCH_QUES_TITLE, VCH_OPTION_1, VCH_OPTION_2, VCH_OPTION_3, VCH_OPTION_4, VCH_CORRECT_OPTION FROM m_test_questions WHERE INT_TEST_ID=testId AND VCH_CREATED_BY=creator AND BIT_DELETED_FLAG=0 ORDER BY INT_QUES_ID DESC ;
END IF;

IF action='editQuestion' THEN
	SELECT INT_QUES_ID, VCH_QUES_TITLE, VCH_OPTION_1, VCH_OPTION_2, VCH_OPTION_3, VCH_OPTION_4, VCH_CORRECT_OPTION FROM m_test_questions WHERE INT_QUES_ID = quesId AND INT_TEST_ID=testId AND VCH_CREATED_BY=creator;
END IF;

IF action='deleteQues' THEN
	DELETE FROM m_test_questions WHERE INT_QUES_ID = quesId AND INT_TEST_ID=testId AND VCH_CREATED_BY=creator;
    
    SELECT 1 AS "msg";
    
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_LOAD_FOR_PROFILE` (IN `action` VARCHAR(20), IN `userId` VARCHAR(20))  NO SQL
BEGIN

IF action='loadAdminProfile' THEN
	SELECT VCH_UNI_ID, VCH_ADMIN_NAME, VCH_MOBILE_NUMBER, VCH_EMAIL_ID, VCH_ADDRESS, VCH_CASTE, VCH_CATEGORY, VCH_GENDER FROM m_admin WHERE VCH_UNI_ID=userId AND BIT_DELETED_FLAG=0;
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_REGISTER_STUDENT` (IN `action` VARCHAR(20), IN `sname` VARCHAR(50), IN `semail` VARCHAR(100), IN `sclass` VARCHAR(10), IN `ssection` VARCHAR(20), IN `sadno` INT, IN `createdby` VARCHAR(50), IN `classroll` INT(5), IN `tempPass` VARCHAR(250))  NO SQL
BEGIN
DECLARE usr int;
DECLARE chk int;

IF action='insertStudent' THEN

SET usr=(SELECT COUNT(1) FROM m_students WHERE INT_ADMISSION_NO=sadno AND BIT_DELETED_FLAG=0);

SET chk=(SELECT COUNT(1) FROM m_students WHERE VCH_STU_EMAIL=semail AND BIT_DELETED_FLAG=0);

IF(usr=1) THEN
	SELECT 0 as msg;		/* user already present */
ELSE

	IF(chk=1) THEN
    	SELECT 1 as msg;	/* email already registered */
    ELSE

	INSERT INTO m_students (INT_ADMISSION_NO,INT_CLASS_ROLLNO,INT_CLASS_ID,INT_SECTION_ID,VCH_STU_NAME,VCH_STU_EMAIL,VCH_PASSWORD,VCH_CREATED_BY) VALUES 
(sadno,classroll,sclass,ssection,sname,semail,tempPass,createdby);

	SELECT 2 AS msg;
	END IF;
END IF;

END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_REGISTER_STUDENT_DATABIND` (IN `action` VARCHAR(20))  NO SQL
BEGIN

	IF action='loadClass' THEN
    	SELECT INT_CLASS_ID,VCH_CLASS_NAME FROM m_class WHERE BIT_DELETED_FLAG=0;
    END IF;
    
    IF action='loadSection' THEN
    	SELECT INT_SEC_ID,VCH_SECTION_NAME FROM m_section WHERE BIT_DELETED_FLAG=0;
    END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_REGISTER_TEACHER` (IN `action` VARCHAR(20), IN `tname` VARCHAR(100), IN `temail` VARCHAR(100), IN `tunid` VARCHAR(20), IN `c1` INT(1), IN `c2` INT(1), IN `c3` INT(1), IN `c4` INT(1), IN `c5` INT(1), IN `c6` INT(1), IN `c7` INT(1), IN `c8` INT(1), IN `c9` INT(1), IN `c10` INT(1), IN `c11` INT(1), IN `c12` INT(1), IN `tpass` VARCHAR(100))  NO SQL
BEGIN
DECLARE usr int;
DECLARE chk int;

IF action='insertTeacher' THEN

SET usr=(SELECT COUNT(1) FROM m_teachers WHERE 	VCH_UNI_ID=tunid AND BIT_DELETED_FLAG=0);

SET chk=(SELECT COUNT(1) FROM m_teachers WHERE VCH_TEACH_EMAIL=temail AND BIT_DELETED_FLAG=0);

IF(usr=1) THEN
	SELECT 0 as msg;		/* teacher already present */
ELSE

	IF(chk=1) THEN
    	SELECT 1 as msg;	/* email already registered */
    ELSE

	INSERT INTO m_teachers(VCH_UNI_ID,VCH_TEACH_NAME,VCH_TEACH_EMAIL,VCH_TEACH_PASSWORD) 		VALUES (tunid,tname,temail,tpass);
    
    INSERT INTO t_teacher_auth(VCH_UNI_ID,CLASS_1,CLASS_2,CLASS_3,CLASS_4,CLASS_5,CLASS_6,CLASS_7,CLASS_8,CLASS_9,CLASS_10,CLASS_11,CLASS_12) VALUES (tunid,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12);

	SELECT 2 AS msg;
	END IF;
END IF;

END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_REMOVE_TEST` (IN `action` VARCHAR(20), IN `testId` INT, IN `created_by` VARCHAR(20))  NO SQL
BEGIN

IF action = 'deleteTest' THEN
	DELETE FROM m_test WHERE INT_TEST_ID=testId AND VCH_CREATOR_ID=created_by;
    
    DELETE FROM m_test_questions WHERE INT_TEST_ID=testId AND 	VCH_CREATED_BY=created_by;
    
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_SAVE_THIS_ANSWER` (IN `action` VARCHAR(20), IN `stuId` INT, IN `testId` INT, IN `quesId` INT, IN `isCorrect` INT, IN `stuOption` VARCHAR(200))  NO SQL
BEGIN
DECLARE cnt INT;

	IF action='saveThisAnswer' THEN
    	UPDATE t_stu_attending_test SET VCH_STUDENT_ANSWER=stuOption, INT_IS_CORRECT=isCorrect WHERE INT_STU_ID=stuId AND INT_TEST_ID=testId AND INT_QUESTION_ID=quesId;

	SELECT 1 AS "msg";
   
    END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_STU_ATTENDED_TEST` (IN `action` VARCHAR(20), IN `testId` INT, IN `creator` VARCHAR(50))  NO SQL
BEGIN

IF action='stuAttenTest' THEN
	SELECT mstu.INT_ADMISSION_NO,mstu.VCH_STU_NAME,mclass.VCH_CLASS_NAME,msec.VCH_SECTION_NAME,mstream.VCH_STREAM,mstu.VCH_STU_PHONE FROM m_students mstu INNER JOIN t_stu_attending_test sat ON mstu.INT_ADMISSION_NO=sat.INT_STU_ID INNER JOIN m_test mtest ON sat.INT_TEST_ID=mtest.INT_TEST_ID INNER JOIN m_class mclass ON mstu.INT_CLASS_ID=mclass.INT_CLASS_ID INNER JOIN m_section msec ON mstu.INT_SECTION_ID=msec.INT_SEC_ID INNER JOIN m_stream mstream ON mstu.INT_STREAM_ID=mstream.INT_STREAM_ID WHERE sat.INT_TEST_ID=testId AND mtest.VCH_CREATOR_ID=creator GROUP BY mstu.INT_ADMISSION_NO;
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_STU_LOAD_TEST` (IN `action` VARCHAR(20), IN `class_id` INT, IN `section_id` INT, IN `stream_id` INT)  NO SQL
BEGIN

IF action='upComingTest' THEN

	SELECT mt.INT_TEST_ID, mt.VCH_TEST_TITLE, mc.VCH_CLASS_NAME, ms.VCH_SECTION_NAME, mstr.VCH_STREAM, msub.VCH_SUBJECT, mt.DTM_SCHEDULE_DATE, mt.TEST_START_TIME, mt.TEST_END_TIME, mt.INT_IS_TEST_ENDED FROM m_test mt INNER JOIN m_class mc ON mc.INT_CLASS_ID=mt.INT_FOR_CLASS_ID INNER JOIN m_section ms ON mt.INT_FOR_SECTION=ms.INT_SEC_ID INNER JOIN m_stream mstr ON mstr.INT_STREAM_ID=mt.INT_STREAM_ID INNER JOIN m_subjects msub ON msub.INT_SUB_ID=mt.INT_SUBJECT_ID WHERE mt.INT_FOR_CLASS_ID=class_id AND mt.INT_FOR_SECTION=section_id AND mt.INT_STREAM_ID=stream_id AND Date(mt.DTM_SCHEDULE_DATE)>=CURDATE() AND mt.INT_IS_TEST_ENDED=0 AND mt.BIT_DELETED_FLAG=0; 
END IF;

IF action='completedTest' THEN
	SELECT mt.INT_TEST_ID, mt.VCH_TEST_TITLE, mc.VCH_CLASS_NAME, ms.VCH_SECTION_NAME, mstr.VCH_STREAM, msub.VCH_SUBJECT, mt.DTM_SCHEDULE_DATE, mt.TEST_START_TIME, mt.TEST_END_TIME, mt.INT_IS_TEST_ENDED FROM m_test mt INNER JOIN m_class mc ON mc.INT_CLASS_ID=mt.INT_FOR_CLASS_ID INNER JOIN m_section ms ON mt.INT_FOR_SECTION=ms.INT_SEC_ID INNER JOIN m_stream mstr ON mstr.INT_STREAM_ID=mt.INT_STREAM_ID INNER JOIN m_subjects msub ON msub.INT_SUB_ID=mt.INT_SUBJECT_ID WHERE mt.INT_FOR_CLASS_ID=class_id AND mt.INT_FOR_SECTION=section_id AND mt.INT_STREAM_ID=stream_id AND mt.INT_IS_TEST_ENDED=1 AND mt.BIT_DELETED_FLAG=0; 
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_TEA_AUTH_CLASS` (IN `action` VARCHAR(20), IN `teaid` VARCHAR(20))  NO SQL
BEGIN

IF action='teachAuthClass' THEN

SELECT CLASS_1,CLASS_2,CLASS_3,CLASS_4,CLASS_5,CLASS_6,CLASS_7,CLASS_8,CLASS_9,CLASS_10,CLASS_11,CLASS_12 FROM t_teacher_auth WHERE VCH_UNI_ID=teaid;

END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_TEST_RESULT` (IN `action` VARCHAR(20), IN `stuId` INT, IN `testId` INT)  NO SQL
BEGIN
DECLARE total INT;
DECLARE scored INT;
DECLARE chk INT;
IF action='testResult' THEN
	SET total=(SELECT COUNT(INT_TEST_ID) FROM t_stu_attending_test WHERE INT_TEST_ID=testId);
    SET scored=(SELECT COUNT(INT_IS_CORRECT) FROM t_stu_attending_test WHERE INT_TEST_ID=testId AND INT_STU_ID=stuId AND INT_IS_CORRECT=1);
    
    SET chk=(SELECT COUNT(INT_TOTAL_MARKS) FROM m_stu_test_marks WHERE INT_TEST_ID=testId AND INT_STU_ID=stuId);
    
    IF chk > 0 THEN			/* marks already inserted */
    	SELECT INT_TOTAL_MARKS,INT_SCORED_MARKS FROM m_stu_test_marks 	WHERE INT_TEST_ID=testId AND INT_STU_ID=stuId;
    ELSE			/* inserting marks for the first time */
    	INSERT INTO m_stu_test_marks (INT_TEST_ID,INT_STU_ID,INT_TOTAL_MARKS,INT_SCORED_MARKS) VALUES(testId,stuId,total,scored);
    END IF;
    
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_UPDATE_ADMIN` (IN `action` VARCHAR(20), IN `adminId` VARCHAR(20), IN `name` VARCHAR(50), IN `mobile` VARCHAR(10), IN `email` VARCHAR(100), IN `address` VARCHAR(200), IN `caste` VARCHAR(20), IN `categ` VARCHAR(20), IN `gen` VARCHAR(20))  NO SQL
BEGIN

IF action='updateAdminDetails' THEN
	UPDATE m_admin SET VCH_ADMIN_NAME=name,VCH_MOBILE_NUMBER=mobile,VCH_EMAIL_ID=email,VCH_ADDRESS=address,VCH_CASTE=caste,VCH_CATEGORY=categ,VCH_GENDER=gen WHERE VCH_UNI_ID=adminId;
    
    SELECT 1 AS msg;
    
END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_UPDATE_STU_DETAILS` (IN `action` VARCHAR(20), IN `sname` VARCHAR(50), IN `semail` VARCHAR(100), IN `sclass` INT(5), IN `ssection` INT(5), IN `sadno` INT(10), IN `sclassroll` INT(5), IN `updateby` VARCHAR(50), IN `updatedate` DATE, IN `sid` INT)  NO SQL
BEGIN

IF action='updateStudent' THEN
UPDATE m_students
SET VCH_STU_NAME=sname,VCH_STU_EMAIL=semail,INT_CLASS_ID=sclass,INT_SECTION_ID=ssection,INT_ADMISSION_NO=sadno,INT_CLASS_ROLLNO=sclassroll,VCH_UPDATED_BY=updateby,DTM_UPDATED_DATE=updatedate
WHERE 
INT_STU_ID=sid;

SELECT 1 AS data;

END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_UPDATE_TEA_DETAILS` (IN `action` VARCHAR(20), IN `tname` VARCHAR(50), IN `temail` VARCHAR(100), IN `tunid` VARCHAR(20), IN `c1` INT(1), IN `c2` INT(1), IN `c3` INT(1), IN `c4` INT(1), IN `c5` INT(1), IN `c6` INT(1), IN `c7` INT(1), IN `c8` INT(1), IN `c9` INT(1), IN `c10` INT(1), IN `c11` INT(1), IN `c12` INT(1), IN `updatedate` DATE)  NO SQL
IF action='updateTeacher' THEN

UPDATE m_teachers SET VCH_UNI_ID=tunid,VCH_TEACH_NAME=tname,VCH_TEACH_EMAIL=temail,DTM_UPDATED_DATE=updatedate WHERE VCH_UNI_ID=tunid;

UPDATE t_teacher_auth SET CLASS_1=c1,CLASS_2=c2,CLASS_3=c3,CLASS_4=c4,CLASS_5=c5,CLASS_6=c6,CLASS_7=c7,CLASS_8=c8,CLASS_9=c9,CLASS_10=c10,CLASS_11=c11,CLASS_12=c12,DTM_UPDATED_DATE=updatedate WHERE VCH_UNI_ID=tunid;

SELECT 1 AS msg;

END IF$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_UPDATE_TEST` (IN `action` VARCHAR(20), IN `test_title` VARCHAR(120), IN `test_date` DATE, IN `test_StartTime` TIME, IN `class_id` INT, IN `section_id` INT, IN `test_EndTime` TIME, IN `stream_id` INT, IN `subject_id` INT, IN `updator_id` VARCHAR(20), IN `test_id` INT)  NO SQL
BEGIN

IF action = 'updateTest' THEN

	UPDATE m_test SET VCH_TEST_TITLE=test_title, INT_FOR_CLASS_ID=class_id, INT_FOR_SECTION=section_id, INT_SUBJECT_ID=subject_id, INT_STREAM_ID=stream_id, DTM_SCHEDULE_DATE=test_date, TEST_START_TIME=test_StartTime, TEST_END_TIME=test_EndTime, VCH_UPDATOR_ID=updator_id WHERE INT_TEST_ID=test_id;

SELECT 1 AS msg;

END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_USER_ADD_TODAY` (IN `action` VARCHAR(20), IN `createdby` VARCHAR(50), IN `todaydate` DATE)  NO SQL
BEGIN

IF action='stuAddedToday' THEN

	SELECT INT_STU_ID, INT_ADMISSION_NO,INT_CLASS_ROLLNO,INT_CLASS_ID,INT_SECTION_ID,	VCH_STU_NAME,VCH_STU_EMAIL FROM m_students WHERE VCH_CREATED_BY=createdby AND DATE(DTM_CREATED_DATE)=todaydate ORDER BY INT_STU_ID DESC;

END IF;

IF action='teaAddedToday' THEN

SELECT VCH_UNI_ID,VCH_TEACH_PASSWORD,VCH_TEACH_NAME,VCH_TEACH_EMAIL FROM m_teachers WHERE VCH_CREATED_BY=createdby AND DATE(DTM_CREATED_DATE)=todaydate ORDER BY INT_TEACH_ID DESC;

END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `USP_USER_LOGIN` (IN `action` VARCHAR(20), IN `adno` VARCHAR(10), IN `pass` VARCHAR(250))  NO SQL
BEGIN	
DECLARE usr int;
DECLARE chk int;

IF action='stulogin' THEN

SET usr=(SELECT COUNT(1) FROM m_students WHERE INT_ADMISSION_NO=adno AND BIT_DELETED_FLAG=0);

SET chk=(SELECT COUNT(1) FROM m_students WHERE INT_ADMISSION_NO=adno AND VCH_PASSWORD=pass AND BIT_DELETED_FLAG=0);

IF(usr<1) THEN
	SELECT '0' as stumsg;		/* no such user exist */
ELSE

	IF(chk<1) THEN
    	SELECT '1' as stumsg;	/* incorrect password */
        
    ELSE						/* all clear */
    	select INT_ADMISSION_NO, VCH_STU_NAME as stumsg, VCH_USER_TYPE, INT_CLASS_ID, INT_SECTION_ID, INT_STREAM_ID from m_students where INT_ADMISSION_NO=adno AND VCH_PASSWORD=pass AND BIT_DELETED_FLAG=0;	
    END IF;
END IF;
END IF;

IF action='adminlogin' THEN

SET usr=(SELECT COUNT(1) FROM m_admin WHERE VCH_UNI_ID=adno AND BIT_DELETED_FLAG=0);

SET chk=(SELECT COUNT(1) FROM m_admin WHERE VCH_UNI_ID=adno AND VCH_ADMIN_PASSWORD=pass AND BIT_DELETED_FLAG=0);

IF(usr<1) THEN
	SELECT '0' as admimsg;		/* no such user exist */
ELSE

	IF(chk<1) THEN
    	SELECT '1' as admimsg;	/* incorrect password */
    ELSE
    	select INT_ADMIN_ID,VCH_UNI_ID,VCH_ADMIN_NAME as admimsg, VCH_USER_TYPE from m_admin where VCH_UNI_ID=adno AND VCH_ADMIN_PASSWORD=pass AND BIT_DELETED_FLAG=0;
    END IF;
END IF;
END IF;

IF action='teachlogin' THEN

SET usr=(SELECT COUNT(1) FROM m_teachers WHERE VCH_UNI_ID=adno AND BIT_DELETED_FLAG=0);

SET chk=(SELECT COUNT(1) FROM m_teachers WHERE VCH_UNI_ID=adno AND VCH_TEACH_PASSWORD=pass AND BIT_DELETED_FLAG=0);

IF(usr<1) THEN
	SELECT '0' as teacmsg;		/* no such user exist */
ELSE

	IF(chk<1) THEN
    	SELECT '1' as teacmsg;	/* incorrect password */
    ELSE
    	select INT_TEACH_ID,VCH_UNI_ID,VCH_TEACH_NAME as teacmsg, VCH_USER_TYPE from m_teachers where VCH_UNI_ID=adno AND VCH_TEACH_PASSWORD=pass AND BIT_DELETED_FLAG=0;
    END IF;
END IF;
END IF;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `m_admin`
--

CREATE TABLE `m_admin` (
  `INT_ADMIN_ID` int(11) NOT NULL,
  `VCH_UNI_ID` varchar(10) NOT NULL,
  `VCH_ADMIN_NAME` varchar(100) NOT NULL,
  `VCH_ADMIN_PASSWORD` varchar(250) NOT NULL,
  `VCH_MOBILE_NUMBER` varchar(10) DEFAULT NULL,
  `VCH_EMAIL_ID` varchar(50) DEFAULT NULL,
  `VCH_ADDRESS` varchar(200) DEFAULT NULL,
  `VCH_CASTE` varchar(20) DEFAULT NULL,
  `VCH_CATEGORY` varchar(20) DEFAULT NULL,
  `VCH_GENDER` varchar(10) DEFAULT NULL,
  `VCH_USER_TYPE` varchar(10) NOT NULL DEFAULT 'admin',
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_admin`
--

INSERT INTO `m_admin` (`INT_ADMIN_ID`, `VCH_UNI_ID`, `VCH_ADMIN_NAME`, `VCH_ADMIN_PASSWORD`, `VCH_MOBILE_NUMBER`, `VCH_EMAIL_ID`, `VCH_ADDRESS`, `VCH_CASTE`, `VCH_CATEGORY`, `VCH_GENDER`, `VCH_USER_TYPE`, `BIT_DELETED_FLAG`) VALUES
(1, 'ADMIN101', 'Mehul', 'admin', '826451210', 'admin12@gmail.com', 'mango jamshedpur', 'hindu', 'general', 'male', 'admin', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `m_class`
--

CREATE TABLE `m_class` (
  `INT_CLASS_ID` int(11) NOT NULL,
  `VCH_CLASS_NAME` varchar(100) NOT NULL,
  `VCH_CREATED_BY` varchar(100) DEFAULT NULL,
  `VCH_UPDATED_BY` varchar(100) DEFAULT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DTM_UPDATED_DATE` datetime DEFAULT NULL,
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_class`
--

INSERT INTO `m_class` (`INT_CLASS_ID`, `VCH_CLASS_NAME`, `VCH_CREATED_BY`, `VCH_UPDATED_BY`, `DTM_CREATED_DATE`, `DTM_UPDATED_DATE`, `BIT_DELETED_FLAG`) VALUES
(1, 'Class 1', 'Mehul', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', b'0'),
(2, 'Class 2', 'mehul', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', b'0'),
(3, 'Class 3', 'mehul', NULL, '2021-03-20 13:14:48', NULL, b'0'),
(4, 'Class 4', 'mehul', NULL, '2021-03-20 13:14:48', NULL, b'0'),
(5, 'Class 5', 'mehul', NULL, '2021-03-20 13:14:48', NULL, b'0'),
(6, 'Class 6', 'mehul', NULL, '2021-03-20 13:14:48', NULL, b'0'),
(7, 'Class 7', 'mehul', NULL, '2021-03-20 13:14:48', NULL, b'0'),
(8, 'Class 8', 'mehul', NULL, '2021-03-20 13:14:48', NULL, b'0'),
(9, 'Class 9', '', NULL, '2021-03-20 13:14:48', NULL, b'0'),
(10, 'Class 10', '', NULL, '2021-03-20 13:14:48', NULL, b'0'),
(11, 'Class 11', 'mehul', NULL, '2021-03-20 13:14:48', NULL, b'0'),
(12, 'Class 12', 'mehul', NULL, '2021-03-20 13:14:48', NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `m_exam_names`
--

CREATE TABLE `m_exam_names` (
  `INT_EXAM_ID` int(11) NOT NULL,
  `VCH_EXAM_NAME` varchar(50) DEFAULT NULL,
  `VCH_CREATED_BY` varchar(50) DEFAULT NULL,
  `VCH_UPDATED_BY` varchar(50) DEFAULT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DTM_UPDATED_DATE` datetime DEFAULT NULL,
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_exam_names`
--

INSERT INTO `m_exam_names` (`INT_EXAM_ID`, `VCH_EXAM_NAME`, `VCH_CREATED_BY`, `VCH_UPDATED_BY`, `DTM_CREATED_DATE`, `DTM_UPDATED_DATE`, `BIT_DELETED_FLAG`) VALUES
(1, 'SA-1', NULL, NULL, '2021-04-03 22:56:47', NULL, b'0'),
(2, 'SA-2', NULL, NULL, '2021-04-03 22:56:47', NULL, b'0'),
(3, 'FA-1', NULL, NULL, '2021-04-03 22:56:47', NULL, b'0'),
(4, 'SA-3', NULL, NULL, '2021-04-03 22:56:47', NULL, b'0'),
(5, 'SA-4', NULL, NULL, '2021-04-03 22:56:47', NULL, b'0'),
(6, 'FA-2', NULL, NULL, '2021-04-03 22:56:47', NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `m_section`
--

CREATE TABLE `m_section` (
  `INT_SEC_ID` int(11) NOT NULL,
  `VCH_SECTION_NAME` varchar(20) DEFAULT NULL,
  `VCH_CREATED_BY` varchar(50) DEFAULT NULL,
  `VCH_UPDATED_BY` varchar(50) DEFAULT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DTM_UPDATED_DATE` datetime DEFAULT NULL,
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_section`
--

INSERT INTO `m_section` (`INT_SEC_ID`, `VCH_SECTION_NAME`, `VCH_CREATED_BY`, `VCH_UPDATED_BY`, `DTM_CREATED_DATE`, `DTM_UPDATED_DATE`, `BIT_DELETED_FLAG`) VALUES
(1, 'Section A', NULL, NULL, '2021-03-20 14:04:56', NULL, b'0'),
(2, 'Section B', NULL, NULL, '2021-03-20 14:04:56', NULL, b'0'),
(3, 'Section C', NULL, NULL, '2021-03-20 14:04:56', NULL, b'0'),
(4, 'Section D', NULL, NULL, '2021-03-20 14:04:56', NULL, b'0'),
(5, 'Section E', NULL, NULL, '2021-04-03 22:53:38', NULL, b'0'),
(6, 'Section F', NULL, NULL, '2021-04-03 22:53:38', NULL, b'0'),
(7, 'Section G', NULL, NULL, '2021-04-03 22:53:38', NULL, b'0'),
(8, 'Section H', 'Section H', NULL, '2021-04-03 22:53:38', NULL, b'0'),
(9, 'All', NULL, NULL, '2021-04-03 22:53:38', NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `m_stream`
--

CREATE TABLE `m_stream` (
  `INT_STREAM_ID` int(11) NOT NULL,
  `VCH_STREAM` varchar(50) DEFAULT NULL,
  `VCH_CREATED_BY` varchar(50) DEFAULT NULL,
  `VCH_UPDATED_BY` varchar(50) DEFAULT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DTM_UPDATED_DATE` datetime DEFAULT NULL,
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_stream`
--

INSERT INTO `m_stream` (`INT_STREAM_ID`, `VCH_STREAM`, `VCH_CREATED_BY`, `VCH_UPDATED_BY`, `DTM_CREATED_DATE`, `DTM_UPDATED_DATE`, `BIT_DELETED_FLAG`) VALUES
(1, 'NA', 'mehul', NULL, '2021-04-03 22:51:11', NULL, b'0'),
(2, 'PCM', 'mehul', NULL, '2021-04-03 22:51:11', NULL, b'0'),
(3, 'PCB', 'mehul', NULL, '2021-04-03 22:51:11', NULL, b'0'),
(4, 'Commerce', 'mehul', NULL, '2021-04-03 22:51:11', NULL, b'0'),
(5, 'Accounts', 'mehul', NULL, '2021-04-03 22:51:11', NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `m_students`
--

CREATE TABLE `m_students` (
  `INT_STU_ID` int(11) NOT NULL,
  `INT_ADMISSION_NO` int(11) NOT NULL,
  `INT_CLASS_ROLLNO` int(11) DEFAULT NULL,
  `INT_CLASS_ID` int(5) DEFAULT NULL,
  `INT_SECTION_ID` int(5) DEFAULT NULL,
  `INT_STREAM_ID` int(11) DEFAULT NULL,
  `VCH_PASSWORD` varchar(250) NOT NULL,
  `VCH_STU_NAME` varchar(100) NOT NULL,
  `VCH_STU_FATHER_NAME` varchar(100) DEFAULT NULL,
  `VCH_STU_MOTHER_NAME` varchar(100) DEFAULT NULL,
  `VCH_STU_EMAIL` varchar(150) DEFAULT NULL,
  `VCH_STU_PHONE` varchar(12) DEFAULT NULL,
  `VCH_PARENT_PHONE` varchar(12) DEFAULT NULL,
  `VCH_STU_ADDRESS` varchar(250) DEFAULT NULL,
  `VCH_STU_GENDER` varchar(10) DEFAULT NULL,
  `VCH_STU_CASTE` varchar(20) DEFAULT NULL,
  `VCH_STU_CATEGORY` varchar(20) DEFAULT NULL,
  `DTM_STU_DOJ` datetime DEFAULT NULL,
  `DTM_STU_DOB` datetime DEFAULT NULL,
  `INT_STU_AGE` int(11) DEFAULT NULL,
  `VCH_USER_TYPE` varchar(10) NOT NULL DEFAULT 'student',
  `VCH_CREATED_BY` varchar(100) DEFAULT NULL,
  `VCH_UPDATED_BY` varchar(100) DEFAULT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DTM_UPDATED_DATE` datetime DEFAULT NULL,
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_students`
--

INSERT INTO `m_students` (`INT_STU_ID`, `INT_ADMISSION_NO`, `INT_CLASS_ROLLNO`, `INT_CLASS_ID`, `INT_SECTION_ID`, `INT_STREAM_ID`, `VCH_PASSWORD`, `VCH_STU_NAME`, `VCH_STU_FATHER_NAME`, `VCH_STU_MOTHER_NAME`, `VCH_STU_EMAIL`, `VCH_STU_PHONE`, `VCH_PARENT_PHONE`, `VCH_STU_ADDRESS`, `VCH_STU_GENDER`, `VCH_STU_CASTE`, `VCH_STU_CATEGORY`, `DTM_STU_DOJ`, `DTM_STU_DOB`, `INT_STU_AGE`, `VCH_USER_TYPE`, `VCH_CREATED_BY`, `VCH_UPDATED_BY`, `DTM_CREATED_DATE`, `DTM_UPDATED_DATE`, `BIT_DELETED_FLAG`) VALUES
(1, 100, 100, 1, 1, 2, 'stu10908198', 'Mehul sharma', 'rajeshwar sharma', 'kanti sharma', 'mehulsharma1714@gmail.com', '6203120616', '7050334966', 'post office road gour basti mango jasmhedpur', 'male', 'hindu', 'general', '2021-02-17 00:00:00', NULL, 20, 'student', 'ADMIN101', 'ADMIN101', '2021-03-26 20:17:45', '2021-03-31 00:00:00', b'0'),
(2, 101, 10, 1, 1, 3, 'stu10716977', 'harshdeep', NULL, NULL, 'hardeepjandu2727@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'student', 'TEA102', NULL, '2021-03-26 21:07:17', NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `m_stu_test_marks`
--

CREATE TABLE `m_stu_test_marks` (
  `INT_ID` int(11) NOT NULL,
  `INT_TEST_ID` int(11) NOT NULL,
  `INT_STU_ID` int(11) NOT NULL,
  `INT_TOTAL_MARKS` int(11) NOT NULL,
  `INT_SCORED_MARKS` int(11) NOT NULL,
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m_subjects`
--

CREATE TABLE `m_subjects` (
  `INT_SUB_ID` int(11) NOT NULL,
  `VCH_SUBJECT` varchar(100) DEFAULT NULL,
  `INT_STREAM_ID` int(11) DEFAULT NULL,
  `INT_CLASS_ID` int(11) DEFAULT NULL,
  `INT_SECTION_ID` int(11) DEFAULT NULL,
  `VCH_CREATED_BY` varchar(50) DEFAULT NULL,
  `VCH_UPDATED_BY` varchar(50) DEFAULT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DTM_UPDATED_DATE` datetime DEFAULT NULL,
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_subjects`
--

INSERT INTO `m_subjects` (`INT_SUB_ID`, `VCH_SUBJECT`, `INT_STREAM_ID`, `INT_CLASS_ID`, `INT_SECTION_ID`, `VCH_CREATED_BY`, `VCH_UPDATED_BY`, `DTM_CREATED_DATE`, `DTM_UPDATED_DATE`, `BIT_DELETED_FLAG`) VALUES
(1, 'Computer', 1, 1, 1, '0', NULL, '2021-04-03 22:48:06', NULL, b'0'),
(2, 'English', 1, 2, 2, '0', NULL, '2021-04-03 22:48:06', NULL, b'0'),
(3, 'Computer Science', 1, 1, 1, NULL, NULL, '2021-04-05 23:40:47', NULL, b'0'),
(4, 'Physics', 1, 1, 1, NULL, NULL, '2021-04-05 23:40:47', NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `m_teachers`
--

CREATE TABLE `m_teachers` (
  `INT_TEACH_ID` int(11) NOT NULL,
  `VCH_UNI_ID` varchar(10) DEFAULT NULL,
  `VCH_TEACH_PASSWORD` varchar(250) NOT NULL,
  `VCH_TEACH_NAME` varchar(100) NOT NULL,
  `VCH_TEACH_FATHER_NAME` varchar(100) DEFAULT NULL,
  `VCH_TEACH_MOTHER_NAME` varchar(100) DEFAULT NULL,
  `VCH_TEACH_EMAIL` varchar(150) DEFAULT NULL,
  `VCH_TEACH_PHONE` varchar(12) DEFAULT NULL,
  `VCH_TEACH_ADDRESS` varchar(200) DEFAULT NULL,
  `VCH_TEACH_GENDER` varchar(10) DEFAULT NULL,
  `VCH_TEACH_CASTE` varchar(20) DEFAULT NULL,
  `VCH_TEACH_CATEGORY` varchar(20) DEFAULT NULL,
  `DTM_TEACH_DOJ` datetime DEFAULT NULL,
  `DTM_TEACH_DOB` datetime DEFAULT NULL,
  `INT_TEACH_AGE` int(11) DEFAULT NULL,
  `VCH_CREATED_BY` varchar(100) DEFAULT NULL,
  `VCH_UPDATED_BY` varchar(100) DEFAULT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DTM_UPDATED_DATE` datetime DEFAULT NULL,
  `VCH_USER_TYPE` varchar(10) NOT NULL DEFAULT 'teacher',
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_teachers`
--

INSERT INTO `m_teachers` (`INT_TEACH_ID`, `VCH_UNI_ID`, `VCH_TEACH_PASSWORD`, `VCH_TEACH_NAME`, `VCH_TEACH_FATHER_NAME`, `VCH_TEACH_MOTHER_NAME`, `VCH_TEACH_EMAIL`, `VCH_TEACH_PHONE`, `VCH_TEACH_ADDRESS`, `VCH_TEACH_GENDER`, `VCH_TEACH_CASTE`, `VCH_TEACH_CATEGORY`, `DTM_TEACH_DOJ`, `DTM_TEACH_DOB`, `INT_TEACH_AGE`, `VCH_CREATED_BY`, `VCH_UPDATED_BY`, `DTM_CREATED_DATE`, `DTM_UPDATED_DATE`, `VCH_USER_TYPE`, `BIT_DELETED_FLAG`) VALUES
(1, 'TEA102', 'teacher', 'mary', '', '', 'mary@gmail.com', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 'ADMIN101', '', '2021-04-01 19:41:13', '2021-04-03 00:00:00', 'teacher', b'0'),
(2, 'TEA103', 'teajenny129362', 'jenny', NULL, NULL, 'jenny@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ADMIN101', NULL, '2021-04-01 13:55:27', NULL, 'teacher', b'0'),
(3, 'TEA104', 'teateacher2654064', 'teacher2', NULL, NULL, 'teach2@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ADMIN101', NULL, '2021-03-30 14:02:45', NULL, 'teacher', b'0'),
(4, 'TEA105', 'teateacher2249238', 'teacher2', NULL, NULL, 'teacher2@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ADMIN101', NULL, '2021-03-30 14:05:22', NULL, 'teacher', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `m_test`
--

CREATE TABLE `m_test` (
  `INT_TEST_ID` int(11) NOT NULL,
  `VCH_TEST_TITLE` varchar(200) DEFAULT NULL,
  `INT_FOR_CLASS_ID` int(11) DEFAULT NULL,
  `INT_FOR_SECTION` int(11) DEFAULT NULL,
  `INT_SUBJECT_ID` int(11) DEFAULT NULL,
  `INT_STREAM_ID` int(11) DEFAULT NULL,
  `DTM_SCHEDULE_DATE` date DEFAULT NULL,
  `TEST_END_TIME` time DEFAULT NULL,
  `TEST_START_TIME` time DEFAULT NULL,
  `INT_TOTAL_QUES` int(11) DEFAULT NULL,
  `BIT_TEST_ASSIGN` bit(1) DEFAULT b'0',
  `VCH_CREATOR_ID` varchar(20) DEFAULT NULL,
  `VCH_UPDATOR_ID` varchar(20) DEFAULT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DTM_UPDATED_DATE` datetime DEFAULT NULL,
  `INT_IS_TEST_ENDED` int(1) NOT NULL DEFAULT '0',
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_test`
--

INSERT INTO `m_test` (`INT_TEST_ID`, `VCH_TEST_TITLE`, `INT_FOR_CLASS_ID`, `INT_FOR_SECTION`, `INT_SUBJECT_ID`, `INT_STREAM_ID`, `DTM_SCHEDULE_DATE`, `TEST_END_TIME`, `TEST_START_TIME`, `INT_TOTAL_QUES`, `BIT_TEST_ASSIGN`, `VCH_CREATOR_ID`, `VCH_UPDATOR_ID`, `DTM_CREATED_DATE`, `DTM_UPDATED_DATE`, `INT_IS_TEST_ENDED`, `BIT_DELETED_FLAG`) VALUES
(2, 'COMPUTER', 1, 1, 3, 2, '2021-04-25', '08:00:00', '06:00:00', NULL, b'0', 'ADMIN101', NULL, '2021-04-06 12:19:46', NULL, 1, b'0'),
(3, 'english', 1, 1, 1, 2, '2021-04-07', '23:55:00', '23:00:00', NULL, b'0', 'ADMIN101', 'ADMIN101', '2021-04-06 13:41:20', NULL, 1, b'0'),
(4, 'testing after fields change', 1, 1, 1, 2, '2021-04-28', '14:53:00', '14:00:00', NULL, b'0', 'ADMIN101', NULL, '2021-04-11 12:51:11', NULL, 0, b'0'),
(5, 'testing new 2', 1, 1, 3, 2, '2021-04-11', '15:00:00', '14:30:00', NULL, b'0', 'ADMIN101', NULL, '2021-04-11 13:33:23', NULL, 1, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `m_test_questions`
--

CREATE TABLE `m_test_questions` (
  `INT_QUES_ID` int(11) NOT NULL,
  `INT_TEST_ID` int(11) DEFAULT NULL,
  `VCH_QUES_TITLE` varchar(500) NOT NULL,
  `VCH_OPTION_1` varchar(150) DEFAULT NULL,
  `VCH_OPTION_2` varchar(150) DEFAULT NULL,
  `VCH_OPTION_3` varchar(150) DEFAULT NULL,
  `VCH_OPTION_4` varchar(150) DEFAULT NULL,
  `VCH_CORRECT_OPTION` varchar(150) DEFAULT NULL,
  `INT_FOR_CLASS_ID` int(11) NOT NULL,
  `INT_FOR_SECTION_ID` int(11) NOT NULL,
  `INT_STREAM_ID` int(11) DEFAULT NULL,
  `INT_SUBJECT_ID` int(11) DEFAULT NULL,
  `VCH_CREATED_BY` varchar(100) NOT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_test_questions`
--

INSERT INTO `m_test_questions` (`INT_QUES_ID`, `INT_TEST_ID`, `VCH_QUES_TITLE`, `VCH_OPTION_1`, `VCH_OPTION_2`, `VCH_OPTION_3`, `VCH_OPTION_4`, `VCH_CORRECT_OPTION`, `INT_FOR_CLASS_ID`, `INT_FOR_SECTION_ID`, `INT_STREAM_ID`, `INT_SUBJECT_ID`, `VCH_CREATED_BY`, `DTM_CREATED_DATE`, `BIT_DELETED_FLAG`) VALUES
(1, 4, 'Father of nation', 'mahatama gandhi', 'Lal Bahadur Sashtri', 'Narendra Modi', 'APJ Abdul Kalam', 'mahatama gandhi', 1, 1, 2, 1, 'ADMIN101', '2021-04-26 20:31:58', b'0'),
(2, 4, 'Brain of computer is called ?', 'Logical Processing Unit', 'Memory Unit', 'Computer\'s Brain', 'CPU', 'CPU', 1, 1, 2, 1, 'ADMIN101', '2021-04-26 20:32:39', b'0'),
(3, 4, 'First satellite of india', 'COMCASA ', 'APOLLO 11', 'ARYABHATT', 'PIEONER', 'ARYABHATT', 1, 1, 2, 1, 'ADMIN101', '2021-04-26 20:33:55', b'0'),
(4, 4, 'Capital of India', 'Delhi', 'Mumbai', 'Chennai', 'Pune', 'Delhi', 1, 1, 2, 1, 'ADMIN101', '2021-04-26 20:35:30', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `t_ques_options`
--

CREATE TABLE `t_ques_options` (
  `INT_ID` int(11) NOT NULL,
  `INT_QUES_ID` int(11) NOT NULL,
  `VCH_OPTION_1` varchar(200) NOT NULL,
  `VCH_OPTION_2` varchar(200) NOT NULL,
  `VCH_OPTION_3` varchar(200) NOT NULL,
  `VCH_OPTION_4` varchar(200) NOT NULL,
  `VCH_CORRECT_OPTION` varchar(200) NOT NULL,
  `VCH_CREATED_BY` varchar(100) NOT NULL,
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_stu_academic`
--

CREATE TABLE `t_stu_academic` (
  `INT_ID` int(11) NOT NULL,
  `INT_STU_ID` int(11) NOT NULL,
  `INT_SUB_ID` int(11) NOT NULL,
  `INT_EXAM_ID` int(11) NOT NULL,
  `INT_MARKS` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_stu_attending_test`
--

CREATE TABLE `t_stu_attending_test` (
  `INT_ID` int(11) NOT NULL,
  `INT_STU_ID` int(11) NOT NULL,
  `INT_TEST_ID` int(11) DEFAULT NULL,
  `INT_CLASS_ID` int(11) DEFAULT NULL,
  `INT_SECTION_ID` int(11) DEFAULT NULL,
  `INT_STREAM_ID` int(11) DEFAULT NULL,
  `INT_QUESTION_ID` int(11) DEFAULT NULL,
  `VCH_QUESTION_TITLE` varchar(500) DEFAULT NULL,
  `VCH_OPTION_1` varchar(200) DEFAULT NULL,
  `VCH_OPTION_2` varchar(200) DEFAULT NULL,
  `VCH_OPTION_3` varchar(200) DEFAULT NULL,
  `VCH_OPTION_4` varchar(200) DEFAULT NULL,
  `VCH_CORRECT_ANSWER` varchar(150) DEFAULT NULL,
  `VCH_STUDENT_ANSWER` varchar(150) DEFAULT NULL,
  `INT_IS_CORRECT` int(1) NOT NULL DEFAULT '0',
  `INT_IS_ATTENDED` int(1) NOT NULL DEFAULT '0',
  `DTM_CREATED_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `BIT_STUDENT_BLOCKED` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_stu_attending_test`
--

INSERT INTO `t_stu_attending_test` (`INT_ID`, `INT_STU_ID`, `INT_TEST_ID`, `INT_CLASS_ID`, `INT_SECTION_ID`, `INT_STREAM_ID`, `INT_QUESTION_ID`, `VCH_QUESTION_TITLE`, `VCH_OPTION_1`, `VCH_OPTION_2`, `VCH_OPTION_3`, `VCH_OPTION_4`, `VCH_CORRECT_ANSWER`, `VCH_STUDENT_ANSWER`, `INT_IS_CORRECT`, `INT_IS_ATTENDED`, `DTM_CREATED_DATE`, `BIT_STUDENT_BLOCKED`) VALUES
(1, 100, 4, 1, 1, 2, 1, 'Father of nation', 'mahatama gandhi', 'Lal Bahadur Sashtri', 'Narendra Modi', 'APJ Abdul Kalam', 'mahatama gandhi', NULL, 0, 0, '2021-04-27 13:30:14', b'0'),
(2, 100, 4, 1, 1, 2, 2, 'Brain of computer is called ?', 'Logical Processing Unit', 'Memory Unit', 'Computer\'s Brain', 'CPU', 'CPU', NULL, 0, 0, '2021-04-27 13:30:14', b'0'),
(3, 100, 4, 1, 1, 2, 3, 'First satellite of india', 'COMCASA ', 'APOLLO 11', 'ARYABHATT', 'PIEONER', 'ARYABHATT', NULL, 0, 0, '2021-04-27 13:30:14', b'0'),
(4, 100, 4, 1, 1, 2, 4, 'Capital of India', 'Delhi', 'Mumbai', 'Chennai', 'Pune', 'Delhi', NULL, 0, 0, '2021-04-27 13:30:14', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `t_stu_exam_marks`
--

CREATE TABLE `t_stu_exam_marks` (
  `INT_ID` int(11) NOT NULL,
  `INT_STU_ID` int(11) DEFAULT NULL,
  `INT_EXAM_ID` int(11) DEFAULT NULL,
  `INT_MARKS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_teacher_auth`
--

CREATE TABLE `t_teacher_auth` (
  `INT_ID` int(11) NOT NULL,
  `VCH_UNI_ID` varchar(10) NOT NULL,
  `CLASS_1` int(1) DEFAULT NULL,
  `CLASS_2` int(1) DEFAULT NULL,
  `CLASS_3` int(1) DEFAULT NULL,
  `CLASS_4` int(1) DEFAULT NULL,
  `CLASS_5` int(1) DEFAULT NULL,
  `CLASS_6` int(1) DEFAULT NULL,
  `CLASS_7` int(1) DEFAULT NULL,
  `CLASS_8` int(1) DEFAULT NULL,
  `CLASS_9` int(1) DEFAULT NULL,
  `CLASS_10` int(1) DEFAULT NULL,
  `CLASS_11` int(1) DEFAULT NULL,
  `CLASS_12` int(1) DEFAULT NULL,
  `DTM_CREATED_DATE` datetime DEFAULT CURRENT_TIMESTAMP,
  `DTM_UPDATED_DATE` date DEFAULT NULL,
  `BIT_DELETED_FLAG` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_teacher_auth`
--

INSERT INTO `t_teacher_auth` (`INT_ID`, `VCH_UNI_ID`, `CLASS_1`, `CLASS_2`, `CLASS_3`, `CLASS_4`, `CLASS_5`, `CLASS_6`, `CLASS_7`, `CLASS_8`, `CLASS_9`, `CLASS_10`, `CLASS_11`, `CLASS_12`, `DTM_CREATED_DATE`, `DTM_UPDATED_DATE`, `BIT_DELETED_FLAG`) VALUES
(1, 'TEA102', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2021-04-01 00:00:00', '2021-04-03', b'0'),
(2, 'TEA103', 1, 1, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, '2021-04-01 00:00:00', NULL, b'0'),
(3, 'TEA104', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2021-03-30 00:00:00', NULL, b'0'),
(4, 'TEA105', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, '2021-03-30 00:00:00', NULL, b'0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_admin`
--
ALTER TABLE `m_admin`
  ADD PRIMARY KEY (`INT_ADMIN_ID`),
  ADD UNIQUE KEY `admin_unique_id` (`VCH_UNI_ID`);

--
-- Indexes for table `m_class`
--
ALTER TABLE `m_class`
  ADD PRIMARY KEY (`INT_CLASS_ID`);

--
-- Indexes for table `m_exam_names`
--
ALTER TABLE `m_exam_names`
  ADD PRIMARY KEY (`INT_EXAM_ID`);

--
-- Indexes for table `m_section`
--
ALTER TABLE `m_section`
  ADD PRIMARY KEY (`INT_SEC_ID`);

--
-- Indexes for table `m_stream`
--
ALTER TABLE `m_stream`
  ADD PRIMARY KEY (`INT_STREAM_ID`);

--
-- Indexes for table `m_students`
--
ALTER TABLE `m_students`
  ADD PRIMARY KEY (`INT_STU_ID`),
  ADD UNIQUE KEY `VCH_ADMISSION_NO` (`INT_ADMISSION_NO`);

--
-- Indexes for table `m_stu_test_marks`
--
ALTER TABLE `m_stu_test_marks`
  ADD PRIMARY KEY (`INT_ID`);

--
-- Indexes for table `m_subjects`
--
ALTER TABLE `m_subjects`
  ADD PRIMARY KEY (`INT_SUB_ID`);

--
-- Indexes for table `m_teachers`
--
ALTER TABLE `m_teachers`
  ADD PRIMARY KEY (`INT_TEACH_ID`),
  ADD UNIQUE KEY `teach_unid` (`VCH_UNI_ID`);

--
-- Indexes for table `m_test`
--
ALTER TABLE `m_test`
  ADD PRIMARY KEY (`INT_TEST_ID`);

--
-- Indexes for table `m_test_questions`
--
ALTER TABLE `m_test_questions`
  ADD PRIMARY KEY (`INT_QUES_ID`);

--
-- Indexes for table `t_ques_options`
--
ALTER TABLE `t_ques_options`
  ADD PRIMARY KEY (`INT_ID`);

--
-- Indexes for table `t_stu_academic`
--
ALTER TABLE `t_stu_academic`
  ADD PRIMARY KEY (`INT_ID`);

--
-- Indexes for table `t_stu_attending_test`
--
ALTER TABLE `t_stu_attending_test`
  ADD PRIMARY KEY (`INT_ID`);

--
-- Indexes for table `t_stu_exam_marks`
--
ALTER TABLE `t_stu_exam_marks`
  ADD PRIMARY KEY (`INT_ID`);

--
-- Indexes for table `t_teacher_auth`
--
ALTER TABLE `t_teacher_auth`
  ADD PRIMARY KEY (`INT_ID`),
  ADD UNIQUE KEY `unique_key` (`VCH_UNI_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_admin`
--
ALTER TABLE `m_admin`
  MODIFY `INT_ADMIN_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `m_class`
--
ALTER TABLE `m_class`
  MODIFY `INT_CLASS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `m_exam_names`
--
ALTER TABLE `m_exam_names`
  MODIFY `INT_EXAM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `m_section`
--
ALTER TABLE `m_section`
  MODIFY `INT_SEC_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `m_stream`
--
ALTER TABLE `m_stream`
  MODIFY `INT_STREAM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_students`
--
ALTER TABLE `m_students`
  MODIFY `INT_STU_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_stu_test_marks`
--
ALTER TABLE `m_stu_test_marks`
  MODIFY `INT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_subjects`
--
ALTER TABLE `m_subjects`
  MODIFY `INT_SUB_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_teachers`
--
ALTER TABLE `m_teachers`
  MODIFY `INT_TEACH_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_test`
--
ALTER TABLE `m_test`
  MODIFY `INT_TEST_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_test_questions`
--
ALTER TABLE `m_test_questions`
  MODIFY `INT_QUES_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_ques_options`
--
ALTER TABLE `t_ques_options`
  MODIFY `INT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_stu_academic`
--
ALTER TABLE `t_stu_academic`
  MODIFY `INT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_stu_attending_test`
--
ALTER TABLE `t_stu_attending_test`
  MODIFY `INT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_stu_exam_marks`
--
ALTER TABLE `t_stu_exam_marks`
  MODIFY `INT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_teacher_auth`
--
ALTER TABLE `t_teacher_auth`
  MODIFY `INT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
