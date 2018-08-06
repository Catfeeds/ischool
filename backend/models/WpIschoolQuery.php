<?php
namespace backend\models;
use Yii;
class WpIschoolQuery extends \yii\base\Model
{
	public static function getSchool($params)
	{

		$from_unix_time = 0;
		$to_unix_time = 4102419661;
		if(isset($params['from_date']) && isset($params['to_date']))
		{
			$from_unix_time = strtotime($params['from_date']);
			$to_unix_time = strtotime($params['to_date']);
		}
		$today_begin_time = ($from_unix_time==0)?strtotime(date("Y-m-d")):$from_unix_time;
		$today_end_time = ($from_unix_time==0)?$today_begin_time + 86400:$to_unix_time;
		$school_id = \yii::$app->view->params['schoolid'];
		if($school_id ==0){
		return \yii::$app->getDb()->createCommand('select tmp.*,tmp2.* from ( SELECT s.id,s.name,count(DISTINCT t.id) as snum,count(DISTINCT n.stu_id) as bnum,count(DISTINCT n.stu_id)/count(DISTINCT t.id) as brate,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimepa between :fromtime1 and :endtime1 and t.enddatepa > UNIX_TIMESTAMP(NOW())) mnumpa,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimepa between :fromtime2 and :endtime2 and t.enddatepa > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mratepa,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimejx between :fromtime3 and :endtime3 and t.enddatejx > UNIX_TIMESTAMP(NOW())) mnumjx,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimejx between :fromtime4 and :endtime4 and t.enddatejx > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mratejx,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimeqq between :fromtime5 and :endtime5 and t.enddateqq > UNIX_TIMESTAMP(NOW())) mnumqq,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimeqq between :fromtime6 and :endtime6 and t.enddateqq > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mrateqq,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimeck between :fromtime7 and :endtime7 and t.enddateck > UNIX_TIMESTAMP(NOW())) mnumck,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimeck between :fromtime8 and :endtime8 and t.enddateck > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mrateck
FROM wp_ischool_school s LEFT JOIN wp_ischool_student t on t.sid=s.id
LEFT JOIN wp_ischool_pastudent n on n.stu_id= t.id  and n.openid is not null AND n.openid !="" and n.ctime between :fromtime and :endtime
where s.is_deleted =0 GROUP BY s.id) tmp left join (select st.sid,count(distinct card.stuid) as cnum,count(distinct card.stuid)/count(distinct st.id) as crate from wp_ischool_student as st left join wp_ischool_safecard as card on st.id = card.stuid and card.ctime between :today_begin_time and :today_end_time group by st.sid)tmp2 on tmp.id = tmp2.sid   order by tmp.id ASC ',[":fromtime1"=>$from_unix_time,":endtime1"=>$to_unix_time,":fromtime2"=>$from_unix_time,":endtime2"=>$to_unix_time,":fromtime3"=>$from_unix_time,":endtime3"=>$to_unix_time,":fromtime4"=>$from_unix_time,":endtime4"=>$to_unix_time,":fromtime5"=>$from_unix_time,":endtime5"=>$to_unix_time,":fromtime6"=>$from_unix_time,":endtime6"=>$to_unix_time,":fromtime7"=>$from_unix_time,":endtime7"=>$to_unix_time,":fromtime8"=>$from_unix_time,":endtime8"=>$to_unix_time,":fromtime"=>$from_unix_time,":endtime"=>$to_unix_time,":today_begin_time"=>$today_begin_time,":today_end_time"=>$today_end_time])->queryAll();
		}else{
			return \yii::$app->getDb()->createCommand('select tmp.*,tmp2.* from ( SELECT s.id,s.name,count(DISTINCT t.id) as snum,count(DISTINCT n.stu_id) as bnum,count(DISTINCT n.stu_id)/count(DISTINCT t.id) as brate,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimepa between :fromtime1 and :endtime1 and t.enddatepa > UNIX_TIMESTAMP(NOW())) mnumpa,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimepa between :fromtime2 and :endtime2 and t.enddatepa > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mratepa,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimejx between :fromtime3 and :endtime3 and t.enddatejx > UNIX_TIMESTAMP(NOW())) mnumjx,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimejx between :fromtime4 and :endtime4 and t.enddatejx > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mratejx,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimeqq between :fromtime5 and :endtime5 and t.enddateqq > UNIX_TIMESTAMP(NOW())) mnumqq,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimeqq between :fromtime6 and :endtime6 and t.enddateqq > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mrateqq,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimeck between :fromtime7 and :endtime7 and t.enddateck > UNIX_TIMESTAMP(NOW())) mnumck,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.sid=s.id AND  t.upendtimeck between :fromtime8 and :endtime8 and t.enddateck > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mrateck
FROM wp_ischool_school s LEFT JOIN wp_ischool_student t on t.sid=s.id
LEFT JOIN wp_ischool_pastudent n on n.stu_id= t.id  and n.openid is not null AND n.openid !="" and n.ctime between :fromtime and :endtime
where s.id= :school_id and s.is_deleted =0 GROUP BY s.id) tmp left join (select st.sid,count(distinct card.stuid) as cnum,count(distinct card.stuid)/count(distinct st.id) as crate from wp_ischool_student as st left join wp_ischool_safecard as card on st.id = card.stuid and card.ctime between :today_begin_time and :today_end_time group by st.sid)tmp2 on tmp.id = tmp2.sid   order by tmp.id ASC ',[":fromtime1"=>$from_unix_time,":endtime1"=>$to_unix_time,":fromtime2"=>$from_unix_time,":endtime2"=>$to_unix_time,":fromtime3"=>$from_unix_time,":endtime3"=>$to_unix_time,":fromtime4"=>$from_unix_time,":endtime4"=>$to_unix_time,":fromtime5"=>$from_unix_time,":endtime5"=>$to_unix_time,":fromtime6"=>$from_unix_time,":endtime6"=>$to_unix_time,":fromtime7"=>$from_unix_time,":endtime7"=>$to_unix_time,":fromtime8"=>$from_unix_time,":endtime8"=>$to_unix_time,":fromtime"=>$from_unix_time,":endtime"=>$to_unix_time,":today_begin_time"=>$today_begin_time,":today_end_time"=>$today_end_time,":school_id"=>$school_id])->queryAll();
		}
	}

	public static function gettongji($params){
		$from_unix_time = 0;
		$to_unix_time = 4102419661;
		if(isset($params['from_date']) && isset($params['to_date']))
		{
			$from_unix_time = strtotime($params['from_date']);
			$to_unix_time = strtotime($params['to_date']);
		}
		$today_begin_time = ($from_unix_time==0)?strtotime(date("Y-m-d")):$from_unix_time;
		$today_end_time = ($from_unix_time==0)?$today_begin_time + 86400:$to_unix_time;
		$school_id = \yii::$app->view->params['schoolid'];
		if($school_id ==0)
		{
			return \yii::$app->getDb()->createCommand('select tmp.* from ( SELECT count(DISTINCT t.id) as snum,count(DISTINCT n.stu_id) as bnum,count(DISTINCT n.stu_id)/count(DISTINCT t.id) as brate,
(select count(distinct card.stuid) from  wp_ischool_safecard as card where  card.ctime between :today_begin_time and :today_end_time) as cnum,
(select count(distinct card.stuid) from  wp_ischool_safecard as card where  card.ctime between :today_begin_time and :today_end_time)/(select count(id) from wp_ischool_student) as crate,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.upendtimepa between :fromtime1 and :endtime1 and t.enddatepa > UNIX_TIMESTAMP(NOW())) mnumpa,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.upendtimepa between :fromtime2 and :endtime2 and t.enddatepa > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mratepa,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.upendtimejx between :fromtime3 and :endtime3 and t.enddatejx > UNIX_TIMESTAMP(NOW())) mnumjx,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.upendtimejx between :fromtime4 and :endtime4 and  t.enddatejx > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mratejx,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.upendtimeqq between :fromtime5 and :endtime5 and  t.enddateqq > UNIX_TIMESTAMP(NOW())) mnumqq,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.upendtimeqq between :fromtime6 and :endtime6 and t.enddateqq > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mrateqq,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.upendtimeck between :fromtime7 and :endtime7 and t.enddateck > UNIX_TIMESTAMP(NOW())) mnumck,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.upendtimeck between :fromtime8 and :endtime8 and t.enddateck > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mrateck
FROM wp_ischool_student t
LEFT JOIN wp_ischool_pastudent n on n.stu_id= t.id  and n.openid is not null AND n.openid !="" and  n.ctime between :fromtime and :endtime
) tmp',[":today_begin_time"=>$today_begin_time,":today_end_time"=>$today_end_time,":today_begin_time2"=>$today_begin_time,":today_end_time2"=>$today_end_time,":fromtime1"=>$from_unix_time,":endtime1"=>$to_unix_time,":fromtime2"=>$from_unix_time,":endtime2"=>$to_unix_time,":fromtime3"=>$from_unix_time,":endtime3"=>$to_unix_time,":fromtime4"=>$from_unix_time,":endtime4"=>$to_unix_time,":fromtime5"=>$from_unix_time,":endtime5"=>$to_unix_time,":fromtime6"=>$from_unix_time,":endtime6"=>$to_unix_time,":fromtime7"=>$from_unix_time,":endtime7"=>$to_unix_time,":fromtime8"=>$from_unix_time,":endtime8"=>$to_unix_time,":fromtime"=>$from_unix_time,":endtime"=>$to_unix_time])->queryAll();
		}
	}

	public static function getClass($sid)
	{
		return \yii::$app->getDb()->createCommand('SELECT c.sid,c.id,c.name,count(DISTINCT t.id) as cnum,count(DISTINCT n.stu_id) as bnum,count(DISTINCT n.stu_id)/count(DISTINCT t.id) as brate,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.cid=c.id and t.enddatepa > UNIX_TIMESTAMP(NOW())) mnumpa,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.cid=c.id and t.enddatepa > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mratepa,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.cid=c.id and t.enddatejx > UNIX_TIMESTAMP(NOW())) mnumjx,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.cid=c.id and t.enddatejx > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mratejx,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.cid=c.id and t.enddateqq > UNIX_TIMESTAMP(NOW())) mnumqq,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.cid=c.id and t.enddateqq > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mrateqq,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.cid=c.id and t.enddateck > UNIX_TIMESTAMP(NOW())) mnumck,
(SELECT count(t.id) as a FROM wp_ischool_student t where t.cid=c.id and t.enddateck > UNIX_TIMESTAMP(NOW()))/count(DISTINCT t.id) as mrateck,
count(DISTINCT d.stuid) as dnum,count(DISTINCT d.stuid)/count(DISTINCT t.id) as drate FROM wp_ischool_class c LEFT JOIN wp_ischool_student t on t.cid= c.id LEFT JOIN wp_ischool_pastudent n on n.stu_id= t.id and n.openid !="" 
 LEFT JOIN wp_ischool_safecard d on d.stuid= t.id WHERE ( c.sid = :sid ) GROUP BY c.id',[":sid"=>$sid])->queryAll();
	}
	public static function getSafecard($sid)
	{
		return \yii::$app->getDb()->createCommand('SELECT t.id,s.name,t.class,t.name as stuName,d.ctime,d.info FROM wp_ischool_school s,wp_ischool_student t,wp_ischool_safecard as d where  t.sid= s.id and s.is_deleted = 0  and  d.stuid= t.id and ( s.id = :sid ) ORDER BY d.ctime desc',[":sid"=>$sid])->queryAll();
	}
	public static function getFee($sid)
	{
		return \yii::$app->getDb()->createCommand('SELECT t.id,s.name,t.class,t.name as stuName,t.upendtimepa,t.enddatepa,t.upendtimejx,t.enddatejx,t.upendtimeqq,t.enddateqq,t.upendtimeck,t.enddateck FROM wp_ischool_school s LEFT JOIN wp_ischool_student t on t.sid= s.id   LEFT JOIN wp_ischool_safecard d on d.stuid= t.id WHERE ( s.id = :sid and s.is_deleted = 0  ) GROUP BY t.id ORDER BY t.class desc',[":sid"=>$sid])->queryAll();
	}
	public static function getBind($sid)
	{
		return \yii::$app->getDb()->createCommand('SELECT t.id,s.name,t.stuno2,n.stu_name,t.class FROM wp_ischool_school s LEFT JOIN wp_ischool_student t on t.sid= s.id LEFT JOIN wp_ischool_pastudent n on n.stu_id= t.id WHERE ( s.id = :sid AND n.stu_id= t.id and s.is_deleted = 0 and n.openid is not null )  GROUP BY t.id ORDER BY t.ctime desc',[":sid"=>$sid])->queryAll();
	}
	public static function getConnect($sid)
	{
		return \yii::$app->getDb()->createCommand('SELECT t3.outopenid,t3.school as sname,t3.class as class,t3.`name` AS sendUser,t3.stu_name as stuName,COUNT(t3.outopenid) AS sendNum from ( SELECT t.id,t.outopenid,t.content,t2.school,t2.class,t2.`name`,t2.stu_name from wp_ischool_outbox t LEFT JOIN wp_ischool_pastudent t2 on t.outopenid=t2.openid where t.type=0 and t2.sid=:sid) t3 GROUP BY t3.name ORDER BY COUNT(t3.outopenid) DESC',[":sid"=>$sid])->queryAll();
	}


	public static function getWeibangding($params){
		if(empty($params['school'])){
			$params['school'] ="正梵高级中学";
		}
		if(isset($params['role'])){
			if($params['role']=='yjfwbd'){
				$sql = "select a.id, a.name,a.class,a.school from wp_ischool_student a
where school LIKE  :schools and (a.enddatejx>UNIX_TIMESTAMP(NOW()) or enddateqq > UNIX_TIMESTAMP(NOW()) or  enddateck > UNIX_TIMESTAMP(NOW())) AND a.id  NOT in(select DISTINCT b.stu_id from wp_ischool_pastudent b where b.school like  :school and b.openid is NOT NULL) ORDER BY a.class";
				return \Yii::$app->getDb()->createCommand($sql,[":schools"=>"%".$params['school']."%",":school"=>"%".$params['school']."%"])->queryAll();
			}elseif($params['role']=='wjf'){
				$sql = "SELECT a.id, a.name,a.class,a.school from wp_ischool_student a WHERE
 enddateqq < UNIX_TIMESTAMP(NOW()) and enddatejx < UNIX_TIMESTAMP(NOW()) and enddateck < UNIX_TIMESTAMP(NOW()) and school LIKE :school ORDER BY a.class";
				return \Yii::$app->getDb()->createCommand($sql,[":school"=>"%".$params['school']."%"])->queryAll();
			}elseif($params['role']=='yjf'){
				$sql = "SELECT a.id, a.name,a.class,a.school from wp_ischool_student a WHERE
 (enddateqq > UNIX_TIMESTAMP(NOW()) or enddatejx > UNIX_TIMESTAMP(NOW()) or enddateck > UNIX_TIMESTAMP(NOW())) and school LIKE :school ORDER BY a.class";
				return \Yii::$app->getDb()->createCommand($sql,[":school"=>"%".$params['school']."%"])->queryAll();
			}elseif($params['role']=='wbd'){
				return \Yii::$app->getDb()->createCommand('select a.id, a.name,a.class,a.school from wp_ischool_student a
where school LIKE :schools  AND a.id NOT in(select DISTINCT b.stu_id from wp_ischool_pastudent b where b.school like :school and b.openid is NOT NULL) ORDER BY a.class',[":schools"=>"%".$params['school']."%",":school"=>"%".$params['school']."%"])->queryAll();
			}else{
				return \Yii::$app->getDb()->createCommand('select a.id, a.name,a.class,a.school from wp_ischool_student a
where school LIKE :schools  AND a.id in(select DISTINCT b.stu_id from wp_ischool_pastudent b where b.school like :school and b.openid is NOT NULL) ORDER BY a.class',[":schools"=>"%".$params['school']."%",":school"=>"%".$params['school']."%"])->queryAll();
			}
		}
	}
}
