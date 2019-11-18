<h2>Рабочий календарь</h2>

{controls}
<div class="controls">
    {form_open}
        <span>Выберите дату:</span>
        {select_date}
        {submit}
    {form_close}
</div>
{/controls}

<br />

{alotment_cal}
<div class="alotment_cal">
    {form_open}
        {year} 
        {month} 
        <span>Необходимо указать все <font color="red">нерабочие</font> дни </span>{submit}
        {calendar}
    {form_close}
</div>
{/alotment_cal}
