<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dashboard
 *
 * @author pnbps
 */
class dashboard extends CI_Model{
	function __construct() {
		parent::__construct();
	}
	function openingClasses(){
		$q = $this->db->query("select count(*)cnt from {$this->db->dbprefix}devClasses where statusId=1");
		$row = $q->row();
		return $row->cnt;
	}
	function employeeEnrolled(){
		$q1 = $this->db->query("select count(*)cnt from {$this->db->dbprefix}devClassEnrollists");
		$row1 = $q1->row();
		$q2 = $this->db->query("select count(*)cnt from {$this->db->dbprefix}devEmployees");
		$row2 = $q2->row();
		return $row1->cnt."/".$row2->cnt;
	}
	function classesExpense(){
		$q = $this->db->query("select sum(amount)cnt from {$this->db->dbprefix}devClassBudgets");
		$row = $q->row();
		return $row->cnt;		
	}
	function quizzes(){
		$q = $this->db->query("select count(*)cnt from {$this->db->dbprefix}devQuizzes");
		$row = $q->row();
		return $row->cnt;
	}
	function listOfOpeningClasses(){
		$q = $this->db->query(""
		. "select {$this->db->dbprefix}devClasses.id CIEntityDataId"
		. ", {$this->db->dbprefix}devCourses.name courseName"
		. ", {$this->db->dbprefix}devSubjects.name subjectName"
		. ", {$this->db->dbprefix}devLocations.code"
		. ", {$this->db->dbprefix}devLocations.descriptions locationDescription"
		. ", CONVERT(varchar(max),{$this->db->dbprefix}devClasses.startDate,103) startDate"
		. ", {$this->db->dbprefix}devClasses.descriptions classDescription"
		. ", {$this->db->dbprefix}devClasses.capacity"
		. ", {$this->db->dbprefix}devClassStatuses.descriptions statusDescription"
		. ",(select count(*) from {$this->db->dbprefix}devClassEnrollists cel where cel.classId={$this->db->dbprefix}devClasses.id)enrolled"
		. "  from {$this->db->dbprefix}devClasses left join {$this->db->dbprefix}devSubjectCourse on (({$this->db->dbprefix}devClasses.scId={$this->db->dbprefix}devSubjectCourse.id)) left join {$this->db->dbprefix}devCourses on (({$this->db->dbprefix}devSubjectCourse.courseId={$this->db->dbprefix}devCourses.id)) left join {$this->db->dbprefix}devSubjects on (({$this->db->dbprefix}devSubjectCourse.subjectId={$this->db->dbprefix}devSubjects.id)) left join {$this->db->dbprefix}devLocations on (({$this->db->dbprefix}devClasses.locationId={$this->db->dbprefix}devLocations.id)) left join {$this->db->dbprefix}devClassStatuses on (({$this->db->dbprefix}devClasses.statusId={$this->db->dbprefix}devClassStatuses.id))	"
		."  where {$this->db->dbprefix}devClasses.statusId=1  and {$this->db->dbprefix}devClasses.createdDate > dateadd(year,-1,getdate()) and {$this->db->dbprefix}devClasses.descriptions not like '%test%' ");
		$tableRow="";
		foreach($q->result() as $row){
			$tableRow.=
				"<tr>"
				. "<td>".$row->courseName."</td>"
				. "<td>".$row->subjectName."</td>"
				. "<td>".$row->code. "</td>"
				. "<td>".$row->locationDescription."</td>"
				. "<td>".$row->startDate. "</td>"
				. "<td>".$row->classDescription. "</td>"
				. "<td>".$row->capacity. "</td>"
				. "<td>".$row->enrolled. "</td>"
				. "</tr>";
		}
		return "<thead><tr><th>Course Name</th><th>Subject Name</th><th>Subject Code</th><th>Location</th><th>Class Start Date</th><th>Class Descriptions</th><th>Capacity</th><th>Enrolled</th></tr></thead><tbody>".$tableRow."</tbody>";	
	}
	
	function viewAllClassesLink(){
		$q = $this->db->query("select id as [index] from {$this->db->dbprefix}sysTasks where taskName='devClasses'; ");
		$row = $q->row();
		return base_url().'m/e/'.$row->index;
	}
}
