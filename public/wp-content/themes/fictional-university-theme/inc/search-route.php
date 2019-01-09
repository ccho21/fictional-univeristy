<?php
/**
 * Created by PhpStorm.
 * User: zzabk
 * Date: 11/10/2018
 * Time: 10:52 PM
 */
?>
<?php


add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch()
{
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults'
    ));
}

function universitySearchResults($data)
{
    $mainQuery = new  WP_Query(
        array(
            'post_type' => array('post', 'page', 'professor', 'program', 'campus','event'),
            's' => sanitize_text_field($data['term']),
        ));
    $result = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array(),
    );
    while($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if(get_post_type()== 'post' OR get_post_type() == 'page'){
            array_push($result['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author(),
            ));
        }
        if(get_post_type()== 'professor'){
            array_push($result['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandScape'),
            ));
        }
        if(get_post_type()== 'program'){
            $relatedCampuses = get_field('related_campus');

            if($relatedCampuses) {
                foreach($relatedCampuses as $campus){
                    array_push($result['campuses'], array(
                       'title' => get_the_title($campus),
                       'permalink' => get_the_permalink($campus),
                    ));
                }
            }


            array_push($result['programs'], array(
                'id' => get_the_id(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        }
        if(get_post_type()== 'event'){
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if (has_excerpt()) {
                $description =  get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            }
            array_push($result['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description,
            ));
        }
        if(get_post_type()== 'campus'){
            array_push($result['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        }

    }

    $programsMetaQuery = array('relation' => 'OR');
    if($result['programs']) {
        foreach($result['programs'] as $item) {
            array_push($programsMetaQuery,  array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"'.$item['id'].'"',
            ));
        }
        $programRelationshipQuery = new WP_Query(
            array(
                'post_type' => array('professor', 'event'),
                'meta_query' => $programsMetaQuery,
            ));

        while($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();

            if(get_post_type()== 'professor'){
                array_push($result['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandScape'),
                ));
            }

            if(get_post_type()== 'event'){
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;
                if (has_excerpt()) {
                    $description =  get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 18);
                }
                array_push($result['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description,
                ));
            }

        }
        $result['professors'] = array_values(array_unique($result['professors'], SORT_REGULAR));
        $result['events'] = array_values(array_unique($result['events'], SORT_REGULAR));
    }

    return $result;
}

?>
