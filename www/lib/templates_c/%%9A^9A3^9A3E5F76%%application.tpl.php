<?php /* Smarty version 2.6.26, created on 2018-01-10 15:22:56
         compiled from application.tpl */ ?>
<?php if ($this->_tpl_vars['arrFDef']): ?>
  <script type="text/javascript">
  // <![CDATA[
  var food = new Array();
  <?php $_from = $this->_tpl_vars['arrFDef']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  food[<?php echo $this->_tpl_vars['key']; ?>
] = "<?php echo $this->_tpl_vars['item']; ?>
"; 
  <?php endforeach; endif; unset($_from); ?>
  // ]]>
  </script>
<?php endif; ?>
<?php if ($this->_tpl_vars['arrTDef']): ?>
  <script type="text/javascript">
  // <![CDATA[
  var toys = new Array();
  <?php $_from = $this->_tpl_vars['arrTDef']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  toys[<?php echo $this->_tpl_vars['key']; ?>
] = "<?php echo $this->_tpl_vars['item']; ?>
";
  <?php endforeach; endif; unset($_from); ?>
  // ]]>
  </script>
<?php endif; ?>
<?php if ($this->_tpl_vars['arrHTDef']): ?>
  <script type="text/javascript">
  // <![CDATA[
  var hideToys = new Array();
  <?php $_from = $this->_tpl_vars['arrHTDef']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  hideToys[<?php echo $this->_tpl_vars['key']; ?>
] = "<?php echo $this->_tpl_vars['item']; ?>
";
  <?php endforeach; endif; unset($_from); ?>
  // ]]>
  </script>
<?php endif; ?>
<?php if ($this->_tpl_vars['arrHFDef']): ?>
  <script type="text/javascript">
  // <![CDATA[
  var hideFood = new Array();
  <?php $_from = $this->_tpl_vars['arrHFDef']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
  hideFood[<?php echo $this->_tpl_vars['key']; ?>
] = "<?php echo $this->_tpl_vars['item']; ?>
";
  <?php endforeach; endif; unset($_from); ?>
  // ]]>
  </script>
<?php endif; ?>
  <script type="text/javascript">
  // <![CDATA[
  var globalhideToys = "<?php echo $this->_tpl_vars['globalHideToys']; ?>
";
  // ]]>
  </script>


  <div id="content-header"><h2>Application For Christmas Assistance - Repl</h2></div>
  <form <?php echo $this->_tpl_vars['form_data']['attributes']; ?>
>
    <table width="100%" id="apptable">
      <tr>
      	<td colspan="6">
          Repl
          <?php $_from = $this->_tpl_vars['form']['errors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['errorloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['errorloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['error']):
        $this->_foreach['errorloop']['iteration']++;
?>
           <?php if (($this->_foreach['errorloop']['iteration'] <= 1)): ?><ul class="error"><?php endif; ?>
           <li><?php echo $this->_tpl_vars['error']; ?>
</li>
           <?php if (($this->_foreach['errorloop']['iteration'] == $this->_foreach['errorloop']['total'])): ?></ul><?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>
	  	</td>
      </tr>
      <tr>
        <th colspan="3"><?php echo $this->_tpl_vars['form_data']['agency']['html']; ?>
&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['agency']['label']; ?>
</th>
		<?php if ($this->_tpl_vars['form_data']['dupe']['html']): ?>
        <td colspan="3"><strong>ADMIN ONLY:</strong> Application is a duplicate: <?php echo $this->_tpl_vars['form_data']['dupe']['html']; ?>
</td>
		<?php endif; ?>      
          
      </tr>


      <tr><th style="line-height:5px;">&nbsp;</th></tr>
	  <tr><th colspan=7 style="background-color:#0C187E; color:#FFF;">Applicant</th></tr>
      <tr>
          <th><?php echo $this->_tpl_vars['form_data']['lastName']['label']; ?>
</th>
          <th><?php echo $this->_tpl_vars['form_data']['firstName']['label']; ?>
</th>
          <th><?php echo $this->_tpl_vars['form_data']['dob_0']['label']; ?>
 <span class="small">(MM-DD-YYYY)</span></th>
          <th><?php echo $this->_tpl_vars['form_data']['ssn_0']['label']; ?>
/Repeat <?php echo $this->_tpl_vars['form_data']['ssn_0_2']['label']; ?>
 <span class="small">(no dash)</span></th>
		<!--
          <th><?php echo $this->_tpl_vars['form_data']['ssn_0']['label']; ?>
 <span class="small">(no dash)</span></th>
          <th><?php echo $this->_tpl_vars['form_data']['ssn_0']['label']; ?>
 <span class="small">(no dash)</span></th>
		-->
      </tr>
      <tr>
          <td><?php echo $this->_tpl_vars['form_data']['lastName']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob_2']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['ssn_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn_2']['html']; ?>
<br/>
          <?php echo $this->_tpl_vars['form_data']['ssn_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn_2_2']['html']; ?>

          </td>
          <!--
          <td><?php echo $this->_tpl_vars['form_data']['ssn_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn_2']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['ssn_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn_2_2']['html']; ?>
</td>
          -->
      </tr>
      <tr><th style="line-height:5px;">&nbsp;</th></tr>

	  <tr><th colspan=7 style="background-color:#0C187E; color:#FFF;">Spouse</th></tr>
      <tr>
          <th><?php echo $this->_tpl_vars['form_data']['lastNameSp']['label']; ?>
</th>
          <th><?php echo $this->_tpl_vars['form_data']['firstNameSp']['label']; ?>
</th>
          <th><?php echo $this->_tpl_vars['form_data']['dobSp']['label']; ?>
 <span class="small">(MM-DD-YYYY)</span></th>
          <th><?php echo $this->_tpl_vars['form_data']['ssnSp_0']['label']; ?>
/Repeat <?php echo $this->_tpl_vars['form_data']['ssnSp_0_2']['label']; ?>
 <span class="small">(no dash)</span></th>
		  <th>&nbsp;</th>
		  <th>&nbsp;</th>
          <th><?php echo $this->_tpl_vars['form_data']['delSp']['label']; ?>
</th>
      </tr>
      <tr>
          <td><?php echo $this->_tpl_vars['form_data']['lastNameSp']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstNameSp']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dobSp_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dobSp_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dobSp_2']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['ssnSp_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssnSp_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssnSp_2']['html']; ?>
<br/>
          <?php echo $this->_tpl_vars['form_data']['ssnSp_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssnSp_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssnSp_2_2']['html']; ?>
</td>
		  <th>&nbsp;</th>
          <td>&nbsp;</td>
          <td><?php echo $this->_tpl_vars['form_data']['delSp']['html']; ?>
</td>
      </tr>
      <tr><th style="line-height:5px;">&nbsp;</th></tr>

	  <tr><th colspan=7 style="background-color:#0C187E; color:#FFF;">Contact Information</th></tr>
      <tr>
          <th><?php echo $this->_tpl_vars['form_data']['phone']['label']; ?>
</th>
          <th><?php echo $this->_tpl_vars['form_data']['street']['label']; ?>
</th>
          <th><?php echo $this->_tpl_vars['form_data']['city']['label']; ?>
</th>
          <td><b><?php echo $this->_tpl_vars['form_data']['state']['label']; ?>
</b></td>
          <td><b><?php echo $this->_tpl_vars['form_data']['zip']['label']; ?>
</b></td>
          <th></th>
          <th></th>
      </tr>
      <tr>
          <td><?php echo $this->_tpl_vars['form_data']['phone']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['street']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['city']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['state']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['zip']['html']; ?>
</td>
          <td></td>
          <td></td>
      </tr>
      <tr><th style="line-height:5px;">&nbsp;</th></tr>

	  <tr><th colspan=7 style="background-color:#0C187E; color:#FFF;">Others in Home</th></tr>

      <tr>
          <th>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName0']['label']; ?>
</th>
          <th><?php echo $this->_tpl_vars['form_data']['firstName0']['label']; ?>
</th>
          <th><?php echo $this->_tpl_vars['form_data']['dob0_0']['label']; ?>
 <span class="small">(MM-DD-YYYY)</span></th>
          <th><?php echo $this->_tpl_vars['form_data']['ssn0_0']['label']; ?>
 / Repeat <?php echo $this->_tpl_vars['form_data']['ssn0_0_2']['label']; ?>
 <span class="small">(no dash)</span></th>
          <th><?php echo $this->_tpl_vars['form_data']['sex0']['label']; ?>
</th>
          <th><?php echo $this->_tpl_vars['form_data']['show']['label']; ?>
</th>
	  <th><?php echo $this->_tpl_vars['form_data']['del0']['label']; ?>
</th>
      </tr>

      <tr>
          <td>&nbsp;1&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName0']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName0']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob0_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob0_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob0_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn0_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn0_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn0_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn0_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn0_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn0_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex0']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del0']['html']; ?>
</td>
      </tr>
	  <tr id="row0" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist0']['html']; ?>
</td>
	  </tr>
      <tr class="alt-row">
          <td>&nbsp;2&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName1']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName1']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob1_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob1_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob1_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn1_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn1_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn1_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn1_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn1_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn1_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex1']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del1']['html']; ?>
</td>
      </tr>
	  <tr id="row1" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist1']['html']; ?>
</td>
	  </tr>
      <tr>
          <td>&nbsp;3&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName2']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName2']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob2_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob2_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob2_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn2_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn2_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn2_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn2_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn2_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn2_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex2']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del2']['html']; ?>
</td>
      </tr>
	  <tr id="row2" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist2']['html']; ?>
</td>
	  </tr>
      <tr class="alt-row">
          <td>&nbsp;4&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName3']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName3']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob3_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob3_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob3_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn3_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn3_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn3_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn3_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn3_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn3_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex3']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del3']['html']; ?>
</td>
      </tr>
	  <tr id="row3" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist3']['html']; ?>
</td>
	  </tr>
      <tr>
          <td>&nbsp;5&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName4']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName4']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob4_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob4_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob4_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn4_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn4_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn4_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn4_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn4_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn4_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex4']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del4']['html']; ?>
</td>
      </tr>
	  <tr id="row4" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist4']['html']; ?>
</td>
	  </tr>
      <tr class="alt-row">
          <td>&nbsp;6&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName5']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName5']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob5_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob5_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob5_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn5_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn5_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn5_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn5_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn5_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn5_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex5']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del5']['html']; ?>
</td>
      </tr>
	  <tr id="row5" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist5']['html']; ?>
</td>
	  </tr>
      <tr>
          <td>&nbsp;7&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName6']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName6']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob6_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob6_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob6_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn6_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn6_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn6_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn6_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn6_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn6_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex6']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del6']['html']; ?>
</td>
      </tr>
	  <tr id="row6" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist6']['html']; ?>
</td>
	  </tr>
      <tr class="alt-row">
          <td>&nbsp;8&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName7']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName7']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob7_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob7_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob7_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn7_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn7_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn7_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn7_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn7_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn7_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex7']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del7']['html']; ?>
</td>
      </tr>
	  <tr id="row7" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist7']['html']; ?>
</td>
	  </tr>
      <tr>
          <td>&nbsp;9&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName8']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName8']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob8_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob8_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob8_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn8_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn8_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn8_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn8_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn8_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn8_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex8']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del8']['html']; ?>
</td>
      </tr>
	  <tr id="row8" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist8']['html']; ?>
</td>
	  </tr>
      <tr class="alt-row">
          <td>10&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName9']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName9']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob9_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob9_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob9_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn9_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn9_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn9_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn9_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn9_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn9_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex9']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del9']['html']; ?>
</td>
      </tr>
	  <tr id="row9" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist9']['html']; ?>
</td>
	  </tr>
      <tr>
          <td>11&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName10']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName10']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob10_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob10_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob10_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn10_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn10_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn10_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn10_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn10_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn10_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex10']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del10']['html']; ?>
</td>
      </tr>
	  <tr id="row10" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist10']['html']; ?>
</td>
	  </tr>
      <tr class="alt-row">
          <td>12&nbsp;<?php echo $this->_tpl_vars['form_data']['lastName11']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['firstName11']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['dob11_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob11_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['dob11_2']['html']; ?>
</td>
          <td>
          		<?php echo $this->_tpl_vars['form_data']['ssn11_0']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn11_1']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn11_2']['html']; ?>
<br/>
          		<?php echo $this->_tpl_vars['form_data']['ssn11_0_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn11_1_2']['html']; ?>
-<?php echo $this->_tpl_vars['form_data']['ssn11_2_2']['html']; ?>

          </td>
          <td><?php echo $this->_tpl_vars['form_data']['sex11']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['show']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['del11']['html']; ?>
</td>
      </tr>
	  <tr id="row11" class="hid">
	  	<td colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['wishlist11']['html']; ?>
</td>
	  </tr>

      <tr>
          <td colspan="6"><span id="disabled"><?php echo $this->_tpl_vars['form_data']['familySize']['html']; ?>
</span>&nbsp;&nbsp;<strong><?php echo $this->_tpl_vars['form_data']['familySize']['label']; ?>
</strong></td>
      </tr>
      <tr>
          <th colspan="6">&nbsp;</th>
      </tr>

      <tr><td colspan="6"></td></tr>
      <tr>
          <td><?php echo $this->_tpl_vars['form_data']['employer']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['income']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['expense']['html']; ?>
</td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
          <td><b><?php echo $this->_tpl_vars['form_data']['employer']['label']; ?>
</b></td>
          <td><b><?php echo $this->_tpl_vars['form_data']['income']['label']; ?>
</b></td>
          <td><b><?php echo $this->_tpl_vars['form_data']['expense']['label']; ?>
</b></td>
          <th></th>
          <th></th>
          <th></th>
      </tr>

      <tr>
          <th colspan="6" align="center">CHECK ALL THAT APPLY</th>
      </tr>
      <tr>
          <th colspan="6" align="center">
		<?php echo $this->_tpl_vars['form_data']['ss']['label']; ?>
<?php echo $this->_tpl_vars['form_data']['ss']['html']; ?>
&nbsp;&nbsp;
		<?php echo $this->_tpl_vars['form_data']['ssi']['label']; ?>
<?php echo $this->_tpl_vars['form_data']['ssi']['html']; ?>
&nbsp;&nbsp;
		<?php echo $this->_tpl_vars['form_data']['va']['label']; ?>
<?php echo $this->_tpl_vars['form_data']['va']['html']; ?>
&nbsp;&nbsp;
		<?php echo $this->_tpl_vars['form_data']['tanf']['label']; ?>
<?php echo $this->_tpl_vars['form_data']['tanf']['html']; ?>
&nbsp;&nbsp;
		<?php echo $this->_tpl_vars['form_data']['fStamp']['label']; ?>
<?php echo $this->_tpl_vars['form_data']['fStamp']['html']; ?>
&nbsp;&nbsp;
		<?php echo $this->_tpl_vars['form_data']['other']['label']; ?>
<?php echo $this->_tpl_vars['form_data']['other']['html']; ?>
&nbsp;&nbsp;
	  </th>
      </tr>
      <tr><td colspan="6">&nbsp;</td></tr>

      <tr>
          <td><?php echo $this->_tpl_vars['form_data']['toys']['html']; ?>
</td>
          <td><?php echo $this->_tpl_vars['form_data']['food']['html']; ?>
</td>
          <td colspan="4"></td>
      </tr>
      <tr>
          <td><b><?php echo $this->_tpl_vars['form_data']['toys']['label']; ?>
</b></td>
          <td><b><?php echo $this->_tpl_vars['form_data']['food']['label']; ?>
</b></td>
          <th colspan="4"></th>
      </tr>

      <tr>
          <td colspan="6"><b><?php echo $this->_tpl_vars['form_data']['comments']['label']; ?>
</b></td>
      </tr>
      <tr>
          <td colspan="6"><?php echo $this->_tpl_vars['form_data']['comments']['html']; ?>
</td>
      </tr>

      <tr>
          <td colspan="6"><?php echo $this->_tpl_vars['form_data']['requirednote']; ?>
</td>
      </tr>

      <tr>
          <th colspan="6">By submitting this form, I certify that all of the information given is accurate and truthful to the best of my knowledge. In addition, I certify that the applicant has given consent to release this information to the Christmas Clearinghouse.</th>
      </tr>

      <tr>
        <td align="center" colspan="6">
         <?php echo $this->_tpl_vars['form_data']['Submit']['html']; ?>
&nbsp;&nbsp;<?php echo $this->_tpl_vars['form_data']['Cancel']['html']; ?>

        </td>
      </tr>
    </table>
  <?php echo $this->_tpl_vars['form_data']['hidden']; ?>

  </form>

<?php if ($this->_tpl_vars['arrFDef']): ?>
  <script type="text/javascript">
  // <![CDATA[
  chgFood();
  // ]]>
  </script>
<?php endif; ?>
<?php if ($this->_tpl_vars['arrTDef']): ?>
  <script type="text/javascript">
  // <![CDATA[
  chgToys();
  // ]]>
  </script>
<?php endif; ?>
Repl 

Repl 