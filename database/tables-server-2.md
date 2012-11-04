# Second Database Server

This server provided services to stone app at kornflex. It contains **3 tables**. Purpose of each table will be clear from context only. 

- - -

## comments

<table>
  <tr>
     <th> commentID </th> <th> stoneID </th> <th> commenter </th> <th> comment</th> <th> time </th> 
  </tr>
  <tr>
    <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> text </td> <td> varchar(50) </td>
  </tr>
</table>


##  readerdata

<table>
  <tr>
    <th> dataID </th> <th> stoneID </th> <th> authorID </th> <th> readerID </th> <th> action </th>
  </tr>
  <tr>
    <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> int(11) </td> <td> varchar(1) </td>
  </tr>
</table>


## stone

<table>
  <tr>
    <th> stoneID </th> <th> userID </th> <th> name </th> <th>title </th> <th>brief </th> <th> story </th> <th> place </th> <th> sources </th> 
  </tr>
  <tr>
    <td> int(11) </td>    <td> int(11) </td> <td> varchar(28) </td> <td> mediumtext </td> <td> varchar(600) </td> <td> text </td> <td> tinytext </td> <td> mediumtext </td> 
  </tr>
</table>


<table>
  <tr>
    <th> time </th> <th> benefitCount </th> <th> readCount </th> <th> campus </th>
  </tr>
  <tr>
    <td> varchar(50)</td><td>int(11)</td><td>int(11)</td><td>varchar(400)</td>
  </tr>
</table>

> Copyright 2012 Krishna Murti


     