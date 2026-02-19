<?php
$style = get_sub_field('faq_style');
$fields = get_sub_field('questions_and_answers');
$length = 0;
if( is_array($fields) ){
    $length = count($fields);
}

$faq_container_class = 'fc-faq-section';
$faq_class = '';
$faq_item_class = 'faq_item';

$question_class = 'faq_question';
$answer_class = 'faq_answer';

if($style == 'plain'){
    $faq_class .= 'plain-faq';
}else{
    $faq_container_class .= ' fc-section-accordion-simple';
    $faq_class .= 'accordion';
    $faq_item_class .= ' accordion-item';
    $question_class = 'accordion-topic';
    $answer_class = 'accordion-response';
    if( $style == 'separated' ){
        $faq_class .= ' separated';
    }
}

$faqs = get_sub_field('questions_and_answers');

?>

<div class="fc-section-columns <?php echo $faq_container_class;?>" id="<?php //echo $anchor;?>">
 <?php get_template_part('flexible/section_header'); ?>
 
  <?php if( is_array( $faqs ) ): ?>

    <div class="uk-container"> 

        <ul class="uk-accordion uk-accordion-default" uk-accordion>

            <?php foreach ($faqs as $faq ): ?>
                <li class="uk-margin-remove-top">
                <a class="uk-accordion-title uk-flex uk-flex-middle uk-flex-between" href><h4 class="uk-margin-top uk-margin-bottom"><?php echo $faq['question'];?></h4> <span uk-icon="icon: chevron-down; ratio: 1.5"></span></a>
                    <div class="uk-accordion-content uk-margin-medium-bottom"><?php echo $faq['answer'];?></div>
                </li>
            <?php endforeach; ?>

        </ul>

    </div>

  <?php endif; ?>
</div>


<script type="application/ld+json">
{
    "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            <?php while ( have_rows('questions_and_answers' ) ): the_row(); //echo get_row_index(); ?>
            
                {
                    "@type": "Question",
                    "name": "<?php echo htmlspecialchars( get_sub_field('question') ); ?>",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "<?php echo htmlspecialchars( get_sub_field('answer') ); ?>"
                    }
                }
                <?php if( $length != get_row_index()  ): ?>
                    ,
                <?php endif;?>

            <?php endwhile; ?>
        ]
}

</script>