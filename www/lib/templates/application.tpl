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
{if $arrHTDef}
  <script type="text/javascript">
  // <![CDATA[
  var hideToys = new Array();
  {foreach from=$arrHTDef key=key item=item}
  hideToys[{$key}] = "{$item}";
  {/foreach}
  // ]]>
  </script>
{/if}
{if $arrHFDef}
  <script type="text/javascript">
  // <![CDATA[
  var hideFood = new Array();
  {foreach from=$arrHFDef key=key item=item}
  hideFood[{$key}] = "{$item}";
  {/foreach}
  // ]]>
  </script>
{/if}
  <script type="text/javascript">
  // <![CDATA[
  var globalhideToys = "{$globalHideToys}";
  // ]]>
  </script>


  <div id="content-header"><h2>Application For Christmas Assistance - {php}echo SEASON{/php}</h2></div>
  <form {$form_data.attributes}>
    <table width="100%" id="apptable">
      <tr>
      	<td colspan="6">
          {* display an overview of all form errors *}
          {foreach from=$form.errors item=error name=errorloop}
           {if $smarty.foreach.errorloop.first}<ul class="error">{/if}
           <li>{$error}</li>
           {if $smarty.foreach.errorloop.last}</ul>{/if}
          {/foreach}
	  	</td>
      </tr>
      <tr>
        <th colspan="3">{$form_data.agency.html}&nbsp;&nbsp;{$form_data.agency.label}</th>
		{if $form_data.dupe.html}
        <td colspan="3"><strong>ADMIN ONLY:</strong> Application is a duplicate: {$form_data.dupe.html}</td>
		{/if}      
          
      </tr>


      <tr><th style="line-height:5px;">&nbsp;</th></tr>
	  <tr><th colspan=7 style="background-color:#0C187E; color:#FFF;">Applicant</th></tr>
      <tr>
          <th>{$form_data.lastName.label}</th>
          <th>{$form_data.firstName.label}</th>
          <th>{$form_data.dob_0.label} <span class="small">(MM-DD-YYYY)</span></th>
          <th>{$form_data.ssn_0.label}/Repeat {$form_data.ssn_0_2.label} <span class="small">(no dash)</span></th>
		<!--
          <th>{$form_data.ssn_0.label} <span class="small">(no dash)</span></th>
          <th>{$form_data.ssn_0.label} <span class="small">(no dash)</span></th>
		-->
      </tr>
      <tr>
          <td>{$form_data.lastName.html}</td>
          <td>{$form_data.firstName.html}</td>
          <td>{$form_data.dob_0.html}-{$form_data.dob_1.html}-{$form_data.dob_2.html}</td>
          <td>{$form_data.ssn_0.html}-{$form_data.ssn_1.html}-{$form_data.ssn_2.html}<br/>
          {$form_data.ssn_0_2.html}-{$form_data.ssn_1_2.html}-{$form_data.ssn_2_2.html}
          </td>
          <!--
          <td>{$form_data.ssn_0.html}-{$form_data.ssn_1.html}-{$form_data.ssn_2.html}</td>
          <td>{$form_data.ssn_0_2.html}-{$form_data.ssn_1_2.html}-{$form_data.ssn_2_2.html}</td>
          -->
      </tr>
      <tr><th style="line-height:5px;">&nbsp;</th></tr>

	  <tr><th colspan=7 style="background-color:#0C187E; color:#FFF;">Spouse</th></tr>
      <tr>
          <th>{$form_data.lastNameSp.label}</th>
          <th>{$form_data.firstNameSp.label}</th>
          <th>{$form_data.dobSp.label} <span class="small">(MM-DD-YYYY)</span></th>
          <th>{$form_data.ssnSp_0.label}/Repeat {$form_data.ssnSp_0_2.label} <span class="small">(no dash)</span></th>
		  <th>&nbsp;</th>
		  <th>&nbsp;</th>
          <th>{$form_data.delSp.label}</th>
      </tr>
      <tr>
          <td>{$form_data.lastNameSp.html}</td>
          <td>{$form_data.firstNameSp.html}</td>
          <td>{$form_data.dobSp_0.html}-{$form_data.dobSp_1.html}-{$form_data.dobSp_2.html}</td>
          <td>{$form_data.ssnSp_0.html}-{$form_data.ssnSp_1.html}-{$form_data.ssnSp_2.html}<br/>
          {$form_data.ssnSp_0_2.html}-{$form_data.ssnSp_1_2.html}-{$form_data.ssnSp_2_2.html}</td>
		  <th>&nbsp;</th>
          <td>&nbsp;</td>
          <td>{$form_data.delSp.html}</td>
      </tr>
      <tr><th style="line-height:5px;">&nbsp;</th></tr>

	  <tr><th colspan=7 style="background-color:#0C187E; color:#FFF;">Contact Information</th></tr>
      <tr>
          <th>{$form_data.phone.label}</th>
          <th>{$form_data.street.label}</th>
          <th>{$form_data.city.label}</th>
          <td><b>{$form_data.state.label}</b></td>
          <td><b>{$form_data.zip.label}</b></td>
          <th></th>
          <th></th>
      </tr>
      <tr>
          <td>{$form_data.phone.html}</td>
          <td>{$form_data.street.html}</td>
          <td>{$form_data.city.html}</td>
          <td>{$form_data.state.html}</td>
          <td>{$form_data.zip.html}</td>
          <td></td>
          <td></td>
      </tr>
      <tr><th style="line-height:5px;">&nbsp;</th></tr>

	  <tr><th colspan=7 style="background-color:#0C187E; color:#FFF;">Others in Home</th></tr>

      <tr>
          <th>&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.lastName0.label}</th>
          <th>{$form_data.firstName0.label}</th>
          <th>{$form_data.dob0_0.label} <span class="small">(MM-DD-YYYY)</span></th>
          <th>{$form_data.ssn0_0.label} / Repeat {$form_data.ssn0_0_2.label} <span class="small">(no dash)</span></th>
          <th>{$form_data.sex0.label}</th>
          <th>{$form_data.show.label}</th>
	  <th>{$form_data.del0.label}</th>
      </tr>

      <tr>
          <td>&nbsp;1&nbsp;{$form_data.lastName0.html}</td>
          <td>{$form_data.firstName0.html}</td>
          <td>{$form_data.dob0_0.html}-{$form_data.dob0_1.html}-{$form_data.dob0_2.html}</td>
          <td>
          		{$form_data.ssn0_0.html}-{$form_data.ssn0_1.html}-{$form_data.ssn0_2.html}<br/>
          		{$form_data.ssn0_0_2.html}-{$form_data.ssn0_1_2.html}-{$form_data.ssn0_2_2.html}
          </td>
          <td>{$form_data.sex0.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del0.html}</td>
      </tr>
	  <tr id="row0" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist0.html}</td>
	  </tr>
      <tr class="alt-row">
          <td>&nbsp;2&nbsp;{$form_data.lastName1.html}</td>
          <td>{$form_data.firstName1.html}</td>
          <td>{$form_data.dob1_0.html}-{$form_data.dob1_1.html}-{$form_data.dob1_2.html}</td>
          <td>
          		{$form_data.ssn1_0.html}-{$form_data.ssn1_1.html}-{$form_data.ssn1_2.html}<br/>
          		{$form_data.ssn1_0_2.html}-{$form_data.ssn1_1_2.html}-{$form_data.ssn1_2_2.html}
          </td>
          <td>{$form_data.sex1.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del1.html}</td>
      </tr>
	  <tr id="row1" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist1.html}</td>
	  </tr>
      <tr>
          <td>&nbsp;3&nbsp;{$form_data.lastName2.html}</td>
          <td>{$form_data.firstName2.html}</td>
          <td>{$form_data.dob2_0.html}-{$form_data.dob2_1.html}-{$form_data.dob2_2.html}</td>
          <td>
          		{$form_data.ssn2_0.html}-{$form_data.ssn2_1.html}-{$form_data.ssn2_2.html}<br/>
          		{$form_data.ssn2_0_2.html}-{$form_data.ssn2_1_2.html}-{$form_data.ssn2_2_2.html}
          </td>
          <td>{$form_data.sex2.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del2.html}</td>
      </tr>
	  <tr id="row2" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist2.html}</td>
	  </tr>
      <tr class="alt-row">
          <td>&nbsp;4&nbsp;{$form_data.lastName3.html}</td>
          <td>{$form_data.firstName3.html}</td>
          <td>{$form_data.dob3_0.html}-{$form_data.dob3_1.html}-{$form_data.dob3_2.html}</td>
          <td>
          		{$form_data.ssn3_0.html}-{$form_data.ssn3_1.html}-{$form_data.ssn3_2.html}<br/>
          		{$form_data.ssn3_0_2.html}-{$form_data.ssn3_1_2.html}-{$form_data.ssn3_2_2.html}
          </td>
          <td>{$form_data.sex3.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del3.html}</td>
      </tr>
	  <tr id="row3" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist3.html}</td>
	  </tr>
      <tr>
          <td>&nbsp;5&nbsp;{$form_data.lastName4.html}</td>
          <td>{$form_data.firstName4.html}</td>
          <td>{$form_data.dob4_0.html}-{$form_data.dob4_1.html}-{$form_data.dob4_2.html}</td>
          <td>
          		{$form_data.ssn4_0.html}-{$form_data.ssn4_1.html}-{$form_data.ssn4_2.html}<br/>
          		{$form_data.ssn4_0_2.html}-{$form_data.ssn4_1_2.html}-{$form_data.ssn4_2_2.html}
          </td>
          <td>{$form_data.sex4.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del4.html}</td>
      </tr>
	  <tr id="row4" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist4.html}</td>
	  </tr>
      <tr class="alt-row">
          <td>&nbsp;6&nbsp;{$form_data.lastName5.html}</td>
          <td>{$form_data.firstName5.html}</td>
          <td>{$form_data.dob5_0.html}-{$form_data.dob5_1.html}-{$form_data.dob5_2.html}</td>
          <td>
          		{$form_data.ssn5_0.html}-{$form_data.ssn5_1.html}-{$form_data.ssn5_2.html}<br/>
          		{$form_data.ssn5_0_2.html}-{$form_data.ssn5_1_2.html}-{$form_data.ssn5_2_2.html}
          </td>
          <td>{$form_data.sex5.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del5.html}</td>
      </tr>
	  <tr id="row5" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist5.html}</td>
	  </tr>
      <tr>
          <td>&nbsp;7&nbsp;{$form_data.lastName6.html}</td>
          <td>{$form_data.firstName6.html}</td>
          <td>{$form_data.dob6_0.html}-{$form_data.dob6_1.html}-{$form_data.dob6_2.html}</td>
          <td>
          		{$form_data.ssn6_0.html}-{$form_data.ssn6_1.html}-{$form_data.ssn6_2.html}<br/>
          		{$form_data.ssn6_0_2.html}-{$form_data.ssn6_1_2.html}-{$form_data.ssn6_2_2.html}
          </td>
          <td>{$form_data.sex6.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del6.html}</td>
      </tr>
	  <tr id="row6" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist6.html}</td>
	  </tr>
      <tr class="alt-row">
          <td>&nbsp;8&nbsp;{$form_data.lastName7.html}</td>
          <td>{$form_data.firstName7.html}</td>
          <td>{$form_data.dob7_0.html}-{$form_data.dob7_1.html}-{$form_data.dob7_2.html}</td>
          <td>
          		{$form_data.ssn7_0.html}-{$form_data.ssn7_1.html}-{$form_data.ssn7_2.html}<br/>
          		{$form_data.ssn7_0_2.html}-{$form_data.ssn7_1_2.html}-{$form_data.ssn7_2_2.html}
          </td>
          <td>{$form_data.sex7.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del7.html}</td>
      </tr>
	  <tr id="row7" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist7.html}</td>
	  </tr>
      <tr>
          <td>&nbsp;9&nbsp;{$form_data.lastName8.html}</td>
          <td>{$form_data.firstName8.html}</td>
          <td>{$form_data.dob8_0.html}-{$form_data.dob8_1.html}-{$form_data.dob8_2.html}</td>
          <td>
          		{$form_data.ssn8_0.html}-{$form_data.ssn8_1.html}-{$form_data.ssn8_2.html}<br/>
          		{$form_data.ssn8_0_2.html}-{$form_data.ssn8_1_2.html}-{$form_data.ssn8_2_2.html}
          </td>
          <td>{$form_data.sex8.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del8.html}</td>
      </tr>
	  <tr id="row8" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist8.html}</td>
	  </tr>
      <tr class="alt-row">
          <td>10&nbsp;{$form_data.lastName9.html}</td>
          <td>{$form_data.firstName9.html}</td>
          <td>{$form_data.dob9_0.html}-{$form_data.dob9_1.html}-{$form_data.dob9_2.html}</td>
          <td>
          		{$form_data.ssn9_0.html}-{$form_data.ssn9_1.html}-{$form_data.ssn9_2.html}<br/>
          		{$form_data.ssn9_0_2.html}-{$form_data.ssn9_1_2.html}-{$form_data.ssn9_2_2.html}
          </td>
          <td>{$form_data.sex9.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del9.html}</td>
      </tr>
	  <tr id="row9" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist9.html}</td>
	  </tr>
      <tr>
          <td>11&nbsp;{$form_data.lastName10.html}</td>
          <td>{$form_data.firstName10.html}</td>
          <td>{$form_data.dob10_0.html}-{$form_data.dob10_1.html}-{$form_data.dob10_2.html}</td>
          <td>
          		{$form_data.ssn10_0.html}-{$form_data.ssn10_1.html}-{$form_data.ssn10_2.html}<br/>
          		{$form_data.ssn10_0_2.html}-{$form_data.ssn10_1_2.html}-{$form_data.ssn10_2_2.html}
          </td>
          <td>{$form_data.sex10.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del10.html}</td>
      </tr>
	  <tr id="row10" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist10.html}</td>
	  </tr>
      <tr class="alt-row">
          <td>12&nbsp;{$form_data.lastName11.html}</td>
          <td>{$form_data.firstName11.html}</td>
          <td>{$form_data.dob11_0.html}-{$form_data.dob11_1.html}-{$form_data.dob11_2.html}</td>
          <td>
          		{$form_data.ssn11_0.html}-{$form_data.ssn11_1.html}-{$form_data.ssn11_2.html}<br/>
          		{$form_data.ssn11_0_2.html}-{$form_data.ssn11_1_2.html}-{$form_data.ssn11_2_2.html}
          </td>
          <td>{$form_data.sex11.html}</td>
          <td>{$form_data.show.html}</td>
          <td>{$form_data.del11.html}</td>
      </tr>
	  <tr id="row11" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;{$form_data.wishlist11.html}</td>
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
          <td><b>{$form_data.employer.label}</b></td>
          <td><b>{$form_data.income.label}</b></td>
          <td><b>{$form_data.expense.label}</b></td>
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
          <td><b>{$form_data.toys.label}</b></td>
          <td><b>{$form_data.food.label}</b></td>
          <th colspan="4"></th>
      </tr>

      <tr>
          <td colspan="6"><b>{$form_data.comments.label}</b></td>
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
{literal}
<script type="text/javascript">
// <![CDATA[
function disableSubmit()
{
	var btn = document.getElementsByName('Submit');
	btn[0].disabled = true;
	btn[0].value = "Please Wait...";
}
document.getElementById("firstForm").onsubmit = disableSubmit;
// ]]>
</script>
{/literal} 

{literal}
<script type="text/javascript">
// <![CDATA[
$(document).ready(function(){
	$('.ssn').mask("999-99-9999",{placeholder:" "});
	$('.ssn').bind('copy paste', function(e) { e.preventDefault(); alert("Copy & Paste is blocked"); });
});
// ]]>
</script>
{/literal} 
