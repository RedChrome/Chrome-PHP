
<br />
<form name="Index" method="POST" action="">

    <input type="text" name="text" value="<?php echo $TOKEN;?>" size="35"/>
    <br />Password <input type="password" name="password" value="" />
    <input type="hidden" name="Index" value="<?php echo $TOKEN;?>" />
    <br /><br />
    <input type="radio" name="radio" value="test" /> Test<br />
    <input type="radio" name="radio" value="test2" /> Test2<br />


    <br />
    <input type="checkbox" name="checkbox[]" value="Value1" /> Value 1<br />
    <input type="checkbox" name="checkbox[]" value="Value2" /> Value 2<br />
    <input type="checkbox" name="checkbox[]" value="Value3" /> Value 3<br />

    <select size="3" name="select[]" multiple="multiple">
        <option value="Value1">Value1</option>
        <option value="Value2">Value2</option>
        <option value="Value3">Value3</option>
    </select>
    <br />
    <br />
    <input type="submit" name="submit" value="Absenden" />


</form>