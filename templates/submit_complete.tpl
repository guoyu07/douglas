{START_FORM}
{message}
<p>Click on a link above to edit a section. Make sure to click the Continue or Finish button to record your changes.</p>
<table cellpadding="4"  border="1" style="width : 100%; margin-bottom : 2em">
    <tr>
        <th style="width : 20%">Name</th>
        <td style="width : 30%">{first_name} {last_name}</td>
        <th style="width : 20%">ASU Box</th>
        <td style="width : 30%">{asu_box}</td>
    </tr>
    <tr>
        <th style="width : 20%">Banner ID</th>
        <td style="width : 30%">{banner_id}</td>
        <th style="width : 20%">ASU user name</th>
        <td style="width : 30%">{asu_username}</td>
    </tr>
    <tr>
        <th>Phone</th>
        <td style="width : 30%">{phone}</td>
        <th>Class</th>
        <td style="width : 30%">{class_year}</td>
    </tr>
    <tr>
        <th style="width : 20%">Major</th>
        <td style="width : 30%">{major}</td>
        <th>GPA</th>
        <td style="width : 30%">{gpa}</td>
    </tr>
    <tr>
        <th style="width : 20%">Current UREC Job(s)</th>
        <td style="width : 30%">{current_jobs}</td>
        <th style="width : 20%">Length of employment</th>
        <td style="width : 30%">{employment_length}</td>
    </tr>
    <tr>
        <th style="width : 20%">Primary supervisor</th>
        <td style="width : 30%">{professional_super}<hr />{professional_super_email}</td>
        <th style="width : 20%">Peer Supervisor</th>
        <td style="width : 30%">{peer_super}</td>
    </tr>
    <tr>
        <th style="width : 20%">Notable contributions to University Recreation</th>
        <td  colspan="3">{contributions}</td>
    </tr>
</table>
<table cellpadding="4" border="1" style="width : 100%; margin-bottom : 2em">
    <tr>
        <th>Organization</th>
        <th>Offices held</th>
        <th>Accomplishments</th>
    </tr>
    <tr>
        <td>{organization1}</td>
        <td>{office1}</td>
        <td>{accomplish1}</td>
    </tr>
    <tr>
        <td>{organization2}</td>
        <td>{office2}</td>
        <td>{accomplish2}</td>
    </tr>
    <tr>
        <td>{organization3}</td>
        <td>{office3}</td>
        <td>{accomplish3}</td>
    </tr>
</table>
<table cellpadding="4" border="1" style="width : 100%; margin-bottom : 2em">
    <tr>
        <th style="width : 33%">Award</th>
        <th style="width : 22%">Received or Nominated Date</th>
        <th style="width : 44%">Short description</th>
    </tr>
    <tr>
        <td>{award1}</td>
        <td>{received1}</td>
        <td>{description1}</td>
    </tr>
    <tr>
        <td>{award2}</td>
        <td>{received2}</td>
        <td>{description2}</td>
    </tr>
    <tr>
        <td>{award3}</td>
        <td>{received3}</td>
        <td>{description3}</td>
    </tr>
</table>
<table cellpadding="4" border="1" style="width : 100%; margin-bottom : 2em">
    <tr>
        <th style="width : 25%">First reference email</th>
        <td>{reference_email1}</td>
    </tr>
    <tr>
        <th style="width : 25%">Second reference email</th>
        <td>{reference_email2}</td>
    </tr>
    <tr>
        <th style="width : 25%">Third reference email</th>
        <td>{reference_email3}</td>
    </tr>
</table>

<table cellpadding="4" border="1" style="width : 100%; margin-bottom : 2em">
    <tr>
        <th style="width : 20%">Resume</th>
        <td>{resume}</td>
    </tr>
    <tr>
        <th style="width : 20%">Essay</th>
        <td>{essay}</td>
    </tr>
</table>
<hr />
{final_authorization}


<div style="text-align : center">{SUBMIT}</div>
{END_FORM}