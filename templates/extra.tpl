{START_FORM}
<fieldset style=" margin-bottom : 20px;"><legend style="font-weight:bold;">Extracurricular Activities</legend>
    <!-- BEGIN error1 --><div style="padding:3px; color : white; font-weight : bold; background-color : red;">{activities}</div><!-- END error1 -->
    <p>Please fill out one to three organizations you were  affiliated with, what office you obtained, and your accomplishments while a member.</p>
<table cellpadding="6" style="width : 100%">
    <tr>
        <th>{ORGANIZATION1_LABEL}</th>
        <th>{OFFICE1_LABEL}</th>
        <th>{ACCOMPLISH1_LABEL}</th>
    </tr>
    <tr>
        <td>{ORGANIZATION1}</td>
        <td>{OFFICE1}</td>
        <td>{ACCOMPLISH1}</td>
    </tr>
    <tr>
        <td>{ORGANIZATION2}</td>
        <td>{OFFICE2}</td>
        <td>{ACCOMPLISH2}</td>
    </tr>
    <tr>
        <td>{ORGANIZATION3}</td>
        <td>{OFFICE3}</td>
        <td>{ACCOMPLISH3}</td>
    </tr>
</table>
</fieldset>

<fieldset><legend style="font-weight:bold">Significant Awards or Honors Nominated or Received</legend>
        <!-- BEGIN error2 --><div style="padding:3px; color : white; font-weight : bold; background-color : red;">{award}</div><!-- END error2 -->
<table cellpadding="6" style="width : 100%">
    <tr>
        <th>Award name</th>
        <th>Received or Nominated/Date</th>
        <th>Short Description</th>
    </tr>
    <tr>
        <td>{AWARD1}</td>
        <td>{RECEIVED1}</td>
        <td>{DESCRIPTION1}</td>
    </tr>
    <tr>
        <td>{AWARD2}</td>
        <td>{RECEIVED2}</td>
        <td>{DESCRIPTION2}</td>
    </tr>
    <tr>
        <td>{AWARD3}</td>
        <td>{RECEIVED3}</td>
        <td>{DESCRIPTION3}</td>
    </tr>
</table>
</fieldset>
{SUBMIT}
{END_FORM}