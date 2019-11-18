<div class="content_desc" style="padding-top: 20px;">
	<h1>Вопрос директору</h1>
	
	{error}
	
	{form_open}
	
	<div style="margin-bottom: 20px;">
		<i><font color="red">*</font> - поля обязательные для заполнения.</i> 
	</div>
	<div class="form-group">
		<label class="col-2 control-label">Ф.И.О.: <font color="red">*</font></label>
		    <div class="col-10">
				{author_from}
		    </div>
	</div>
	<div class="form-group">
		<label class="col-2 control-label">E-mail: <font color="red">*</font></label>
		    <div class="col-10">
				{author_to}
		    </div>
	</div>
	<div class="form-group">
		<label class="col-2 control-label">Вопрос: <font color="red">*</font></label>
		    <div class="col-10">
				{question}
		    </div>
	</div>

	{submit}

	<div class="clearfix"></div>

	{form_close}
	
	<hr style="border: 0; border-bottom: 1px dashed #4B493C;" />
	
	{answers}
	    {answer}
	{/answers}
</div>