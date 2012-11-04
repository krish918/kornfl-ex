# Database server 3

It was last of our server, which was served exclusively for apptease, a quiz app at kornflex. It contains large amount of academic questions and is **rather queried very fastly** than any
other server. I am presnting to you its whole structure. It has **5 tables**.

- - -


## questionbase



<table>
 <tr>
   <th> questionID </th> <th> question </th> <th> field </th> <th> choice1 </th> <th> choice2 </th> <th> choice3 </th> <th> choice4 </th> <th> choice5 </th> 
  </tr>
  <tr >
    <td> int(11) </td> <td> text </td> <td>varchar(20) </td> <td> text </td><td> text </td><td> text </td><td> text </td><td> text </td>
   </tr>
</table>

<table>
	<tr>
		<th> difficultyLevel </th> <th>totalAttemmpt</th> <th> totalCorrect </th> <th>totalPasses</th> <th>TTS</th> <th> description </th> <th> timestamp </th>
	</tr>
        <tr>
    <td> int(2) </td> <td> int(11) </td> <td> int(11) </td><td> int(11) </td><td> int(11) </td> <td> text </td> <td> varchar(100) </td>
	</tr>
</table>



## scores



<table>
  <tr>
    <th> scoreID </th> <th> userID </th> <th> totalScore </th> <th> currentSession </th> <th> totalCorrect </th> <th>totalQuestions </th><th> totalPasses </th> 
  </tr>
  <tr>
     <td> int(11) </td>  <td> int(11) </td>  <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> 
   </tr>
</table>

<table>
<tr><th> totalAttempt </th> <th> lastTimePlayed </th></tr>
<tr><td> int(11) </td> <td> varchar(100) </td></tr>
</table>



## user_attempt



<table> 
 <tr>
   <th> recordID </th> <th> userID </th> <th> questionID </th> <th> answer </th> <th> session </th> <th> correct </th> <th> timeTaken </th> <th> timestamp </th>
 </tr>
 <tr> 
   <td> int(11) </td><td> int(11) </td><td> int(11) </td><td> int(1) </td> <td> int(11) </td> <td> int(3) </td> <td> int(3) </td> <td> varchar(100) </td>
 </tr>
</table>


## user_sessions


<table>
 <tr>
   <th> recordID </th> <th> userID </th> <th> session </th> <th> score </th> <th> questionCount </th> <th> correctCount </th><th> passCount</th> <th> attemptCount </th>
 </tr>
 <tr>
    <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td><td> int(11) </td> 
 </tr>
</table>

<table>
 <tr><th> timestamp </th> <th> sesssionField </th></tr>
 <tr><td> varchar(100) </td> <td> varchar(70) </td></tr>
</table>



> Copyright 2012 Krishna Murti
