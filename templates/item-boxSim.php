
<svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" viewBox="0 0 500 500">

  <?php
$svgBox = jr_boxGen($item) ;

echo "<rect id='item' fill='#407bbf' x='$svgBox[itemX]' y='$svgBox[itemY]'
            height='$svgBox[itemH]' width='$svgBox[itemW]'  >Item</rect>";
if ($svgBox[tableY]) {
  echo "<rect id='table' fill='#40bf71' x='$svgBox[tableX]' y='$svgBox[tableY]'
              height='$svgBox[tableH]' width='$svgBox[tableW]' >Table</rect>";
}

echo "<rect id='man' fill='#BF4040' x='$svgBox[manX]' y='$svgBox[manY]'
            height='$svgBox[manH]' width='$svgBox[manW]' ></rect>"; ?>

</svg>
