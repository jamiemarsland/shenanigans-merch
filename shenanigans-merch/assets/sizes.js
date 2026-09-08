/* Progressive enhancement: WooCommerce retains variation and stock logic. */
(function($){
'use strict';
function enhance(form){
 $(form).find('select[name^="attribute_"]').each(function(){
  const select=this;
  if(!/size/i.test(select.name)||select.dataset.shEnhanced)return;
  select.dataset.shEnhanced='1';
  const group=document.createElement('div');
  group.className='sh-size-options';group.setAttribute('role','group');
  const label=form.querySelector('label[for="'+CSS.escape(select.id)+'"]');
  group.setAttribute('aria-label',label?label.textContent.trim():'Size');
  const choices=Array.from(select.options).filter(o=>o.value).map(o=>({value:o.value,label:o.textContent}));
  select.after(group);
  const buttons=choices.map(choice=>{
   const button=document.createElement('button');button.type='button';button.textContent=choice.label;
   button.addEventListener('click',()=>{$(select).val(choice.value).trigger('change');});
   group.append(button);return button;
  });
  function sync(){
   choices.forEach((choice,i)=>{
    const option=Array.from(select.options).find(o=>o.value===choice.value);
    buttons[i].disabled=!option||option.disabled||select.disabled;
    buttons[i].setAttribute('aria-pressed',select.value===choice.value?'true':'false');
   });
  }
  $(select).on('change',sync);
  $(form).on('woocommerce_update_variation_values reset_data found_variation',sync);
  // Keep the native labelled select accessible; visual buttons are a convenience.
  sync();select.classList.add('sh-size-enhanced');
 });
}
$(function(){$('form.variations_form').each(function(){enhance(this);});});
$(document).on('wc_variation_form','form.variations_form',function(){enhance(this);});
})(jQuery);
