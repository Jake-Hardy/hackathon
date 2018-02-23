  <div id="content-header"><h2>View Application</h2></div>
    <table width="100%">
      <tr>
           <td colspan="5">{$form_data.agency.label}<strong>{$form_data.agency.html}</strong></td>
      </tr>
      <tr>
           <td colspan="5">{$form_data.user.label}<strong>{$form_data.user.html}</strong></td>
      </tr>
      <tr>
	   <td colspan="5">{$form_data.appId.label}<strong>{$form_data.appId.html}</strong></td>
      </tr>
      <tr><td colspan="5">&nbsp;</td></tr>
 
      <tr>
          <th>{$form_data.lastName.html}</th>
          <th>{$form_data.firstName.html}</th>
          <th>{$form_data.dob.html}</th>
          <th>{$form_data.age.html}</th>
          <th></th>
      </tr>
      <tr>
          <td>{$form_data.lastName.label}</td>
          <td>{$form_data.firstName.label}</td>
          <td>{$form_data.dob.label}</td>
          <td>{$form_data.age.label}</td>
          <td></td> 
      </tr>

      <tr>
          <th>{$form_data.lastNameSp.html}</th>
          <th>{$form_data.firstNameSp.html}</th>
          <th>{$form_data.dobSp.html}</th>
          <th>{$form_data.ageSp.html}</th>
          <th></th>
      </tr>
      <tr>
          <td>{$form_data.lastNameSp.label}</td>
          <td>{$form_data.firstNameSp.label}</td>
          <td>{$form_data.dobSp.label}</td>
          <td>{$form_data.ageSp.label}</td>
          <td></td>
      </tr>

      <tr>
          <th colspan="5">{$form_data.phone.html}&nbsp;</th>
      </tr>
      <tr>
          <td colspan="5">{$form_data.phone.label}</td>
      </tr>

      <tr>
          <th>{$form_data.street.html}</th>
          <th>{$form_data.city.html}</th>
          <th>{$form_data.state.html}</th>
          <th>{$form_data.zip.html}</th>
          <th></th>
      </tr>
      <tr>
          <td>{$form_data.street.label}</td>
          <td>{$form_data.city.label}</td>
          <td>{$form_data.state.label}</td>
          <td>{$form_data.zip.label}</td>
          <td></td>
      </tr>
{if $form_data.others}
      <tr>
          <td colspan="5" align="center"><span class="big">OTHERS IN HOME</span></td>
      </tr>
      <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.lastName0.label}</td>
          <td>{$form_data.firstName0.label}</td>
          <td>{$form_data.dob0.label}</td>
	  <td>{$form_data.age0.label}</td>
          <td>{$form_data.sex0.label}</td>
      </tr>
{/if}
{if $form_data.lastName0.html}
      <tr>
          <th>&nbsp;1&nbsp;{$form_data.lastName0.html}</th>
          <th>{$form_data.firstName0.html}</th>
          <th>{$form_data.dob0.html}</th>
	  <th>{$form_data.age0.html}</td>
          <th>{$form_data.sex0.html}</th>
      </tr>
{/if}
{if $form_data.lastName1.html}
      <tr>
          <th>&nbsp;2&nbsp;{$form_data.lastName1.html}</th>
          <th>{$form_data.firstName1.html}</th>
          <th>{$form_data.dob1.html}</th>
          <th>{$form_data.age1.html}</td>
          <th>{$form_data.sex1.html}</th>
      </tr>
{/if}
{if $form_data.lastName2.html}
      <tr>
          <th>&nbsp;3&nbsp;{$form_data.lastName2.html}</th>
          <th>{$form_data.firstName2.html}</th>
          <th>{$form_data.dob2.html}</th>
          <th>{$form_data.age2.html}</td>
          <th>{$form_data.sex2.html}</th>
      </tr>
{/if}
{if $form_data.lastName3.html}
      <tr>
          <th>&nbsp;4&nbsp;{$form_data.lastName3.html}</th>
          <th>{$form_data.firstName3.html}</th>
          <th>{$form_data.dob3.html}</th>
          <th>{$form_data.age3.html}</td>
          <th>{$form_data.sex3.html}</th>
      </tr>
{/if}
{if $form_data.lastName4.html}
      <tr>
          <th>&nbsp;5&nbsp;{$form_data.lastName4.html}</th>
          <th>{$form_data.firstName4.html}</th>
          <th>{$form_data.dob4.html}</th>
          <th>{$form_data.age4.html}</td>
          <th>{$form_data.sex4.html}</th>
      </tr>
{/if}
{if $form_data.lastName5.html}
      <tr>
          <th>&nbsp;6&nbsp;{$form_data.lastName5.html}</th>
          <th>{$form_data.firstName5.html}</th>
          <th>{$form_data.dob5.html}</th>
          <th>{$form_data.age5.html}</td>
          <th>{$form_data.sex5.html}</th>
      </tr>
{/if}
{if $form_data.lastName6.html}
      <tr>
          <th>&nbsp;7&nbsp;{$form_data.lastName6.html}</th>
          <th>{$form_data.firstName6.html}</th>
          <th>{$form_data.dob6.html}</th>
          <th>{$form_data.age6.html}</td>
          <th>{$form_data.sex6.html}</th>
      </tr>
{/if}
{if $form_data.lastName7.html}
      <tr>
          <th>&nbsp;8&nbsp;{$form_data.lastName7.html}</th>
          <th>{$form_data.firstName7.html}</th>
          <th>{$form_data.dob7.html}</th>
          <th>{$form_data.age7.html}</td>
          <th>{$form_data.sex7.html}</th>
      </tr>
{/if}
{if $form_data.lastName8.html}
      <tr>
          <th>&nbsp;9&nbsp;{$form_data.lastName8.html}</th>
          <th>{$form_data.firstName8.html}</th>
          <th>{$form_data.dob8.html}</th>
          <th>{$form_data.age8.html}</td>
          <th>{$form_data.sex8.html}</th>
      </tr>
{/if}
{if $form_data.lastName9.html}
      <tr>
          <th>10&nbsp;{$form_data.lastName9.html}</th>
          <th>{$form_data.firstName9.html}</th>
          <th>{$form_data.dob9.html}</th>
          <th>{$form_data.age9.html}</td>
          <th>{$form_data.sex9.html}</th>
      </tr>
{/if}
{if $form_data.lastName10.html}
      <tr>
          <th>11&nbsp;{$form_data.lastName10.html}</th>
          <th>{$form_data.firstName10.html}</th>
          <th>{$form_data.dob10.html}</th>
          <th>{$form_data.age10.html}</td>
          <th>{$form_data.sex10.html}</th>
      </tr>
{/if}
{if $form_data.lastName11.html}
      <tr>
          <th>12&nbsp;{$form_data.lastName11.html}</th>
          <th>{$form_data.firstName11.html}</th>
          <th>{$form_data.dob11.html}</th>
          <th>{$form_data.age11.html}</td>
          <th>{$form_data.sex11.html}</th>
      </tr>
{/if}
      <tr><td colspan="5">&nbsp;</td></tr>
      <tr>
          <td colspan="5">{$form_data.familySize.label}<strong>{$form_data.familySize.html}</strong></td>
      </tr>
      <tr>
          <th colspan="5">&nbsp;</th>
      </tr>

      <tr>
          <th>{$form_data.employer.html}&nbsp;</th>
          <th>{$form_data.income.html}&nbsp;</th>
          <th>{$form_data.expense.html}&nbsp;</th>
          <th></th>
          <th></th>
      </tr>
      <tr>
          <td>{$form_data.employer.label}</td>
          <td>{$form_data.income.label}</td>
          <td>{$form_data.expense.label}</td>
          <td></td>
          <td></td>
      </tr>

      <tr>
          <th colspan="5" align="center">&nbsp;</th>
      </tr>

      <tr>
          <th colspan="5" align="center">
		{$form_data.ss.label}&nbsp;&nbsp;
		{$form_data.ssi.label}&nbsp;&nbsp;
		{$form_data.va.label}&nbsp;&nbsp;
		{$form_data.tanf.label}&nbsp;&nbsp;
		{$form_data.fStamp.label}&nbsp;&nbsp;
		{$form_data.other.label}&nbsp;&nbsp;
	  </th>
      </tr>
      <tr><td colspan="5">&nbsp;</td></tr>

      <tr>
          <th>{$form_data.toys.html}</th>
          <th>{$form_data.food.html}</th>
          <th colspan="3"></th>
      </tr>
      <tr>
          <td>{$form_data.toys.label}</td>
          <td>{$form_data.food.label}</td>
          <td colspan="3"></td>
      </tr>
{if $form_data.comments.html}
      <tr>
          <td colspan"5"><br />{$form_data.comments.label}</td>
      </tr>
      <tr>
          <td colspan="5" id="comments">{$form_data.comments.html}</td>
      </tr>
{/if}
      <tr>
	  <td colspan="5"><br /><em>{$form_data.tstamp.label}{$form_data.tstamp.html}</em></td>
      </tr>
      <tr>
          <td colspan="5"><em>{$form_data.tstampUpd.label}{$form_data.tstampUpd.html}</em></td>
      </tr>
      <tr>
	  <td colspan="5" align="center"><strong>{$form_data.action.html}{$form_data.action.label}</strong></td>
      </tr>
    </table>
