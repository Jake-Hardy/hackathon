{if $arrFDef}
  <script type="text/javascript">
  // <![CDATA[
  var food = new Array();
  {foreach from=$arrFDef key=key item=item}
  food[{$key}] = "{$item}"; 
  {/foreach}
  // ]]>
  </script>
{/if}
{if $arrTDef}
  <script type="text/javascript">
  // <![CDATA[
  var toys = new Array();
  {foreach from=$arrTDef key=key item=item}
  toys[{$key}] = "{$item}";
  {/foreach}
  // ]]>
  </script>
{/if}

  <div id="content-header"><h2>Application For Christmas Assistance - {php}echo SEASON{/php}</h2></div>
  <form {$form_data.attributes}>
    <table width="100%">
      <tr>
           <th colspan="5">{$form_data.agency.html}&nbsp;&nbsp;{$form_data.agency.label}</th>
      </tr>
      <tr><td colspan="5">&nbsp;</td></tr> 

      <tr>
          <td>{$form_data.lastName.html}</td>
          <td>{$form_data.firstName.html}</td>
          <td>{$form_data.dob.html}</td>
          <td>{$form_data.ssn.html}</td>
          <td></td>
          <td></td>
      </tr>
      <tr>
          <th>{$form_data.lastName.label}</th>
          <th>{$form_data.firstName.label}</th>
          <th>{$form_data.dob.label} <span class="small">(MM-DD-YYYY)</span></th>
          <th>{$form_data.ssn.label} <span class="small">(no dash)</span></th>
          <th></th>
          <th></th>
      </tr>

      <tr>
          <td>{$form_data.lastNameSp.html}</td>
          <td>{$form_data.firstNameSp.html}</td>
          <td>{$form_data.dobSp.html}</td>
          <td>{$form_data.ssnSp.html}</td>
          <td></td>
          <td>{$form_data.delSp.html}</td>
      </tr>
      <tr>
          <th>{$form_data.lastNameSp.label}</th>
          <th>{$form_data.firstNameSp.label}</th>
          <th>{$form_data.dobSp.label} <span class="small">(MM-DD-YYYY)</span></th>
          <th>{$form_data.ssnSp.label} <span class="small">(no dash)</span></th>
          <th></th>
          <th>{$form_data.delSp.label}</th>
      </tr>

      <tr>
          <td colspan="6">{$form_data.phone.html}</td>
      </tr>
      <tr>
          <th colspan="6">{$form_data.phone.label}</th>
      </tr>

      <tr>
          <td>{$form_data.street.html}</td>
          <td>{$form_data.city.html}</td>
          <td>{$form_data.state.html}</td>
          <td>{$form_data.zip.html}</td>
          <td></td>
          <td></td>
      </tr>
      <tr>
          <th>{$form_data.street.label}</th>
          <th>{$form_data.city.label}</th>
          <th>{$form_data.state.label}</th>
          <th>{$form_data.zip.label}</th>
          <th></th>
          <th></th>
      </tr>

      <tr>
          <td colspan="6" align="center"><span class="big">OTHERS IN HOME</span></td>
      </tr>

      <tr>
          <th>&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.lastName0.label}</th>
          <th>{$form_data.firstName0.label}</th>
          <th>{$form_data.dob0.label} <span class="small">(MM-DD-YYYY)</span></th>
          <th>{$form_data.ssn0.label} <span class="small">(no dash)</span></th>
          <th>{$form_data.sex0.label}</th>
	  <th>{$form_data.del0.label}</th>
      </tr>

      <tr>
          <td>&nbsp;1&nbsp;{$form_data.lastName0.html}</td>
          <td>{$form_data.firstName0.html}</td>
          <td>{$form_data.dob0.html}</td>
          <td>{$form_data.ssn0.html}</td>
          <td>{$form_data.sex0.html}</td>
          <td>{$form_data.del0.html}</td>
      </tr>
      <tr>
          <td>&nbsp;2&nbsp;{$form_data.lastName1.html}</td>
          <td>{$form_data.firstName1.html}</td>
          <td>{$form_data.dob1.html}</td>
          <td>{$form_data.ssn1.html}</td>
          <td>{$form_data.sex1.html}</td>
          <td>{$form_data.del1.html}</td>
      </tr>
      <tr>
          <td>&nbsp;3&nbsp;{$form_data.lastName2.html}</td>
          <td>{$form_data.firstName2.html}</td>
          <td>{$form_data.dob2.html}</td>
          <td>{$form_data.ssn2.html}</td>
          <td>{$form_data.sex2.html}</td>
          <td>{$form_data.del2.html}</td>
      </tr>
      <tr>
          <td>&nbsp;4&nbsp;{$form_data.lastName3.html}</td>
          <td>{$form_data.firstName3.html}</td>
          <td>{$form_data.dob3.html}</td>
          <td>{$form_data.ssn3.html}</td>
          <td>{$form_data.sex3.html}</td>
          <td>{$form_data.del3.html}</td>
      </tr>
      <tr>
          <td>&nbsp;5&nbsp;{$form_data.lastName4.html}</td>
          <td>{$form_data.firstName4.html}</td>
          <td>{$form_data.dob4.html}</td>
          <td>{$form_data.ssn4.html}</td>
          <td>{$form_data.sex4.html}</td>
          <td>{$form_data.del4.html}</td>
      </tr>
      <tr>
          <td>&nbsp;6&nbsp;{$form_data.lastName5.html}</td>
          <td>{$form_data.firstName5.html}</td>
          <td>{$form_data.dob5.html}</td>
          <td>{$form_data.ssn5.html}</td>
          <td>{$form_data.sex5.html}</td>
          <td>{$form_data.del5.html}</td>
      </tr>
      <tr>
          <td>&nbsp;7&nbsp;{$form_data.lastName6.html}</td>
          <td>{$form_data.firstName6.html}</td>
          <td>{$form_data.dob6.html}</td>
          <td>{$form_data.ssn6.html}</td>
          <td>{$form_data.sex6.html}</td>
          <td>{$form_data.del6.html}</td>
      </tr>
      <tr>
          <td>&nbsp;8&nbsp;{$form_data.lastName7.html}</td>
          <td>{$form_data.firstName7.html}</td>
          <td>{$form_data.dob7.html}</td>
          <td>{$form_data.ssn7.html}</td>
          <td>{$form_data.sex7.html}</td>
          <td>{$form_data.del7.html}</td>
      </tr>
      <tr>
          <td>&nbsp;9&nbsp;{$form_data.lastName8.html}</td>
          <td>{$form_data.firstName8.html}</td>
          <td>{$form_data.dob8.html}</td>
          <td>{$form_data.ssn8.html}</td>
          <td>{$form_data.sex8.html}</td>
          <td>{$form_data.del8.html}</td>
      </tr>
      <tr>
          <td>10&nbsp;{$form_data.lastName9.html}</td>
          <td>{$form_data.firstName9.html}</td>
          <td>{$form_data.dob9.html}</td>
          <td>{$form_data.ssn9.html}</td>
          <td>{$form_data.sex9.html}</td>
          <td>{$form_data.del9.html}</td>
      </tr>
      <tr>
          <td>11&nbsp;{$form_data.lastName10.html}</td>
          <td>{$form_data.firstName10.html}</td>
          <td>{$form_data.dob10.html}</td>
          <td>{$form_data.ssn10.html}</td>
          <td>{$form_data.sex10.html}</td>
          <td>{$form_data.del10.html}</td>
      </tr>
      <tr>
          <td>12&nbsp;{$form_data.lastName11.html}</td>
          <td>{$form_data.firstName11.html}</td>
          <td>{$form_data.dob11.html}</td>
          <td>{$form_data.ssn11.html}</td>
          <td>{$form_data.sex11.html}</td>
          <td>{$form_data.del11.html}</td>
      </tr>

      <tr>
          <td colspan="6"><span id="disabled">{$form_data.familySize.html}</span>&nbsp;&nbsp;<strong>{$form_data.familySize.label}</strong></td>
      </tr>
      <tr>
          <th colspan="6">&nbsp;</th>
      </tr>

      <tr><td colspan="6"></td></tr>
      <tr>
          <td>{$form_data.employer.html}</td>
          <td>{$form_data.income.html}</td>
          <td>{$form_data.expense.html}</td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
          <th>{$form_data.employer.label}</th>
          <th>{$form_data.income.label}</th>
          <th>{$form_data.expense.label}</th>
          <th></th>
          <th></th>
          <th></th>
      </tr>

      <tr>
          <th colspan="6" align="center">CHECK ALL THAT APPLY</th>
      </tr>
      <tr>
          <th colspan="6" align="center">
		{$form_data.ss.label}{$form_data.ss.html}&nbsp;&nbsp;
		{$form_data.ssi.label}{$form_data.ssi.html}&nbsp;&nbsp;
		{$form_data.va.label}{$form_data.va.html}&nbsp;&nbsp;
		{$form_data.tanf.label}{$form_data.tanf.html}&nbsp;&nbsp;
		{$form_data.fStamp.label}{$form_data.fStamp.html}&nbsp;&nbsp;
		{$form_data.other.label}{$form_data.other.html}&nbsp;&nbsp;
	  </th>
      </tr>
      <tr><td colspan="6">&nbsp;</td></tr>

      <tr>
          <td>{$form_data.toys.html}</td>
          <td>{$form_data.food.html}</td>
          <td colspan="4"></td>
      </tr>
      <tr>
          <th>{$form_data.toys.label}</th>
          <th>{$form_data.food.label}</th>
          <th colspan="4"></th>
      </tr>

      <tr>
          <th colspan="6">{$form_data.comments.label}</th>
      </tr>
      <tr>
          <td colspan="6">{$form_data.comments.html}</td>
      </tr>

      <tr>
          <td colspan="6">{$form_data.requirednote}</td>
      </tr>

      <tr>
          <th colspan="6">By submitting this form, I certify that all of the information given is accurate and truthful to the best of my knowledge. In addition, I certify that the applicant has given consent to release this information to the Christmas Clearinghouse.</th>
      </tr>

      <tr>
        <td align="center" colspan="6">
         {$form_data.Submit.html}&nbsp;&nbsp;{$form_data.Cancel.html}
        </td>
      </tr>
    </table>
  {$form_data.hidden}
  </form>

{if $arrFDef}
  <script type="text/javascript">
  // <![CDATA[
  chgFood();
  // ]]>
  </script>
{/if}
{if $arrTDef}
  <script type="text/javascript">
  // <![CDATA[
  chgToys();
  // ]]>
  </script>
{/if}
 
