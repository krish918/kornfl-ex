# Databases at Kornflex

There are **three** central databases at kornflex. I am providing you the tabular structure of each of them. This information will help you to create more intensive and sophisticated apps.

## First Database server

This server contains most of the user data. **It contains 19 tables.**

>90% of data and information required by Kornflex was served by the first server. We are not telling you the exact hostname of server due to security issue.

Here is the **list and schema** of all the tables

### activationData
<table>

 <tr>
  <th>activationID</th><th>name</th><th>Email</th><th>password</th><th>time</th>
 </tr>
 <tr>
  <td> 09847 </td> <td>Chuck Norris</td> <td>chuck.is.not.on.ahens@ahens.com</td><td>7764ggRRSHH$$jhsjsms.sdjiFTS5678.std</td><td>12/12/12</td>
 </tr>
</table>

<table>
<tr><th>emailsentat</th><th>emailsentcount</th><th>userID</th><th>activationString</th><th>used</th><th>usedAt</th></tr>
<tr><td> 00:00</td><td>12/12/12 00:12</td><td> 213 </td> <td> 007 </td> <td>asdDFFDggs6733bbGSLLSnnshU524 </td> <td>12/12/12 00:24</td></tr>
</table>


### agreelist

<table>
  <tr>
    <th>fieldID</th><th>flakesID</th><th>agreeID</th><th>disagreeID</th>
  </tr>
  <tr>
    <td>int(11)</td><td>int(11)</td><td>int(11)</td><td>int(11)</td>
  </tr>
</table>


### appcetra

<table>
  <tr>
    <th>appID</th><th>appName</th><th>appLink</th><th>appDescription</th><th>timestamp</th><th>totalSubscriptions</th>
   </tr>
   <tr>
     <td>int(11)</td><td>varchar(100)</td><td>varchar(200)</td><td>text</td><td>varchar(100)</td><td>int(11)</td>
   </tr>
</table>

### appsubscription

<table>
  <tr>
    <th>subscriptionID</th><th>userID</th><th>appID</th><th>timestamp</th>
  </tr>
   <tr>
   <td>int(11)</td><td>int(11)</td><td>int(11)</td><td>varchar(100)</td> 
   </tr>
</table>


### campusdata

<table>
 <tr>
   <th>campusID</th><th>campusCode</th><th>campusName</th><th>Address1</th><th>Address2</th><th>City</th><th>State</th><th>Country</th>
 </tr>
 <tr>
  <td>int(11)</td><td>varchar(7)</td><td>varchar(400)</td><td>tinytext</td><td>tinytext</td><td>varchar(30)</td><td>varchar(35)</td><td>varchar(35)</td>
 </tr>
</table>

<table>
<tr>
<th>Estabslished</th><th>NetworkCount</th><th>projectionCount</th></tr>
<tr><td>int(4)</td><td>smallint(6)</td><td>smallint(6)</td></tr>
</table>



### corn_users
 
<table>
  <tr>
    <th> userID </th> <th> userName </th> <th>   Email   </th>   <th> Password </th> <th> LoginCount </th> <th> LastLogin </th> <th> campusJoined </th> <th> profileProjected </th>

  </tr>
  <tr>
     <td> int(11) </td> <td> varchar(23) </td> <td>   varchar(60)   </td>   <td> varchar(55) </td> <td> int(11) </td> <td> varchar(35) </td> <td> varchar(7) </td> <td> tinyint </td> 
  </tr>
</table>

<table>
  <tr>
 <th> profileSaved </th><th> accountCreated </th><th> lastIP </th><th> lastUserInfo </th><th> emailHidden </th><th> timeSpent </th>  <th> live </th>  <th> feedback </th> </tr>
 <tr> 
 <td> tinyint(1) </td> <td> varchar(40) </td><td> varchar(17) </td><td> varchar(255) </td><td> int(1) </td><td> int(20) </td><td> int(1) </td>  </tr>
</table>

<table>
  <tr >
  <th> notificationStat </th> <th> lastUpdateStat </th> <th> lastUpadate </th><th> invitation </th><th> activation </th><th> gender </th></tr>
  <tr><td> int(1) </td>  <td> int(11) </td>  <td> varchar(40) </td> <td> int(11) </td> <td> int(2) </td><td> int(25) </td><td> varchar(14) </td> </tr>
</table>


### feedback (obsolete)
 
<table>
  <tr>
   <th>feedbackID </th> <th>feedback </th><th>suggestion </th><th>feedback2 </th>
  </tr>
  <tr>
   <td>int(11) </td> <td>varchar(100) </td><td>text</td><td>varchar(100)</td> 
  </tr>
</table>

### flakes

<table>
  <tr>
   <th> flakesID </th> <th> userID </th> <th> flaker </th>  <th> campus </th> <th> about </th> <th> flakes </th> <th> time </th> <th> agree </th> <th> disagree </th> <th>hidden</th>
  </tr>
  <tr>
   <td> int(11) </td> <td> int(11) </td> <td> varchar(26) </td> <td> varchar(7) </td> <td> varchar(255) </td> <td> text </td> <td> varchar(40) </td> <td> int(11) </td> <td> int(11) </td>  <td>  int(1) </td>
  </tr>
</table>

### help (obsolete)

<table>
  <tr>
    <th> helpID </th> <th> userID </th> <th> query </th> <th> time</th>
   </tr>
   <tr>
     <td> int(11) </td> <td> int(11) </td> <td> mediumtext </td> <td> varchar(40) </td>
   </tr>
</table>

###kteasers
	
<table>
  <tr>
   <th> teaserId </th> <th> userID </th> <th> teaser </th> <th> version </th> <th> active </th> <th> totalAttempt </th>
  </tr>
  <tr>
   <td> int(11) </td> <td> int(11) </td> <td> mediumtext </td> <td> decimal(2,1) </td> <td> int(1) </td> <td> int(11) </td>
  </tr>
</table>

### link_random_string

<table>
   <tr>
   <th> stringID </th> <th> userID </th> <th> string </th> <th> createdAt </th> <th> used </th> <th> usedAt </th> <th> changedAt </th>
   </tr>
   <tr>
   <td> int(11)</td> <td> varchar(70) </td> <td> varchar(70) </td> <td> varchar(50) </td> <td> int(1) </td> <td> varchar(50) </td> <td> varchar(50) </td>
   </tr>
</table>

### mirror
 
<table>
   <tr>
      <th> threadID </th> <th> threadSource </th> <th> target </th> <th> thread </th> <th> unreadFlag </th> <th> time </th> <th> hidden </th>
     
   </tr>
   <tr>
      <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> varchar(320) </td> <td> int(1) </td> <td> varchar(35) </td> <td> int(1) </td>
   </tr>
</table>

### notification

<table>
   <tr>
     <th> notificationID </th> <th> flakesID </th> <th> profileID </th> <th> raterID </th> <th> userID </th> <th> time </th> <th> author </th> <th> reader </th> <th> writer </th> <th> action </th> 
   </tr>
   <tr>
      <td> int(11) </td>  <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> varchar(35) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(1) </td> 
   </tr>
</table>

<table>
 <tr>
  <th> stoneID </th> <th> commenter </th>
 </tr>
 <tr>
  <td> int(11) </td> <td> int(11) </td>
  </tr>
</table>
   

### pictures

<table>
    <tr>
       <th> picID </th> <th> userID </th> <th> picName </th> <th> picPath </th>
     </tr>
    <tr>
       <td> int(11) </td> <td> int(11) </td>  <td> varchar(255) </td>  <td> varchar(255) </td>
     </tr>
</table>

### profiles

<table>
    <tr>
       <th> profileID </th> <th> userID </th> <th> fullName </th> <th> email </th> <th> profileType </th> <th> campusCode </th> <th> homeTown </th> <th> course </th> 
    </tr>
    <tr>
       <td> int(11) </td> <td> int(11) </td> <td> varchar(23) </td> <td> varchar(60) </td> <td> varchar(11) </td> <td> varchar(7) </td> <td> varchar(50) </td> <td> varchar(10) </td> 

    </tr>   
</table>

<table>
<tr><th> subject </th> <th> year </th> <th> dobDay </th> <th> dobMonth </th> <th> dobYear </th> <th> lastInstitute </th> <th> areaInterest </th> <th> fieldExpertise </th> <th> favoritePlace </th> </tr>
<tr><td> tinytext </td> <td> varchar(10) </td> <td> varchar(3) </td> <td> varchar(10) </td> <td>  varchar(4) </td> <td > tinytext </td> <td> mediumtext </td> <td> mediumtext </td> <td> mediumtext </td> </tr>
</table>

<table>
<tr><th> profileStatement </th> <th>currentLearning </th> <th> currentReading </th> <th> q1 </th> <th> q2 </th> <th> q3 </th> <th> q4 </th> <th> q5 </th></tr>
<tr><td> varchar(140) </td> <td> mediumtext </td>  <td> tinytext </td> <td> mediumtext </td> <td> mediumtext </td><td> mediumtext </td><td> mediumtext </td><td> mediumtext </td> </tr> 
</table>


<table>
 <tr> <th> projected </th> <th> ratings </th> <th> raters </th> <th> averageRatings </th> <th> rank </th> <th> peopleInterested </th></tr>
 <tr><td> int(1) </td> <td> int(11) </td> <td> int(11) </td> <td> float(2,1) </td> <td> int(11) </td> <td> int(11) </td> </tr>
</table>

### profile_visit

<table>
  <tr>
     <th> visitID </th> <th> visitor </th> <th> target </th> <th> time </th>
  </tr>
  <tr>
     <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> varchar(40) </td>
  </tr>
</table>

### ratinglist

<table>
  <tr>
     <th> rateID </th> <th> rater </th> <th> target </th> <th> rating </th><th> isource </th><th> interestedin </th>
  </tr>
  <tr>
     <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> 
  </tr>
</table>

### SESSION_DATA

<table>
   <tr>
       <th> sessionID </th> <th> userID </th> <th> time </th> <th> ip </th> <th> systeminfo </th>
   </tr>
   <tr>
       <td> int(11) </td> <td> int(11) </td> <td> varchar(40) </td> <td> varchar(20) </td> <td> varchar(255) </td>
   </tr>
</table>

### site_stat
	
<table>
   <tr> 
      <th> dataID </th> <th> userID </th> <th> page_address </th> <th> timestamp </th>
   </tr>
   <tr>
      <td> int(11) </td> <td> int(11) </td> <td> varchar(200) </td> <td> varchar(100) </td>
   </tr>
</table>

## Second Server

Second server contains all the information matrix of stone. It is relatively smaller and less busy
server than the first. ** Check the second markdown file** in this directory.


> Copyright 2012 Krishna Murti
	
  


