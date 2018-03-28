<?php

namespace Includes\Modules\Events;

use KeriganSolutions\CPT\CustomPostType;

// Exit if accessed directly.
if ( ! defined('ABSPATH')) {
    exit;
}

class Events
{

    public $timezone;

    /**
     * Events constructor.
     */
    function __construct()
    {
        date_default_timezone_set('America/Chicago');
        $this->timezone = new \DateTimeZone('America/Chicago');
    }

    function setupAdmin()
    {
        $this->createPostType();
        $this->createAdminColumns();
    }

    /**
     * @return null
     */
    public function createPostType()
    {

        $events = new CustomPostType('Event', [
            'supports'           => ['title', 'revisions'],
            'menu_icon'          => 'dashicons-calendar',
            'rewrite'            => ['with_front' => true],
            'has_archive'        => true,
            'menu_position'      => null,
            'public'             => true,
            'publicly_queryable' => true,
        ]);

        $events->addMetaBox('Event Details', [
            'Photo File'           => 'image',
            'Start'                => 'date',
            'End'                  => 'date',
            'Time'                 => 'text',
            'Location'             => 'text',
            'Tickets Link'         => 'text',
            'Full Image'           => 'image'
        ]);

        $events->addMetaBox('Recurring Settings', [
            'Recurring' => [
                'type' => 'select',
                'data' => [
                    'None',
                    'Monthly',
                    'Weekly',
                    'Monthday'
                ]
            ],
            'Monday'    => 'boolean',
            'Tuesday'   => 'boolean',
            'Wednesday' => 'boolean',
            'Thursday'  => 'boolean',
            'Friday'    => 'boolean',
            'Saturday'  => 'boolean',
            'Sunday'    => 'boolean',
            'Weekend'  => 'boolean',
            'Weekday'  => 'boolean'
        ]);

        $events->addMetaBox(
            'Event Description',
            [
                'HTML' => 'wysiwyg',
            ]
        );

        $events->addTaxonomy('Bands');
        $events->convertCheckToRadio( 'bands' );
        $events->createTaxonomyMeta( 'bands', [ 'label' => 'Band Photo', 'type' => 'image' ] );
        $events->createTaxonomyMeta( 'bands', [ 'label' => 'Band Description', 'type' => 'wysiwyg' ] );
        $events->createTaxonomyMeta( 'bands', [ 'label' => 'Facebook Link', 'type' => 'text' ] );
        $events->createTaxonomyMeta( 'bands', [ 'label' => 'Twitter Link', 'type' => 'text' ] );
        $events->createTaxonomyMeta( 'bands', [ 'label' => 'Instagram Link', 'type' => 'text' ] );
        $events->createTaxonomyMeta( 'bands', [ 'label' => 'YouTube Link', 'type' => 'text' ] );
        $events->createTaxonomyMeta( 'bands', [ 'label' => 'Website Link', 'type' => 'text' ] );
    }

    /**
     * @return null
     */
    public function createAdminColumns()
    {

        add_filter('manage_event_posts_columns',
            function ($defaults) {
                $defaults = [
                    'cb'          => '<input type="checkbox" />',
                    'title'       => 'Title',
                    'start'       => 'Start',
                    'end'         => 'End',
                    'time'        => 'Time',
                    'location'    => 'Location',
                    'showdetails' => 'Link to Details',
                    'featured'    => 'Featured on Home Page',
                    'photo'       => 'Photo'
                ];

                return $defaults;
            }, 0);

        add_action('manage_event_posts_custom_column', function ($column_name, $post_ID) {
            switch ($column_name) {
                case 'photo':
                    $photo = get_post_meta($post_ID, 'event_details_photo_file', true);
                    echo(isset($photo) ? '<img src ="' . $photo . '" class="img-fluid" style="width:400px; max-width:100%;" >' : null);
                    break;

                case 'start':
                    $object = get_post_meta($post_ID, 'event_details_start', true);
                    echo(isset($object) ? date('M j, Y', strtotime($object)) : null);
                    break;

                case 'end':
                    $object = get_post_meta($post_ID, 'event_details_end', true);
                    echo(isset($object) ? date('M j, Y', strtotime($object)) : null);
                    break;

                case 'time':
                    $object = get_post_meta($post_ID, 'event_details_time', true);
                    echo(isset($object) ? $object : null);
                    break;

                case 'location':
                    $object = get_post_meta($post_ID, 'event_details_location', true);
                    echo(isset($object) ? $object : null);
                    break;

                case 'showdetails':
                    $featured = get_post_meta($post_ID, 'event_details_show_details', true);
                    echo($featured == 'on' ? 'TRUE' : 'FALSE');
                    break;

                case 'featured':
                    $featured = get_post_meta($post_ID, 'event_details_feature_on_home_page', true);
                    echo($featured == 'on' ? 'TRUE' : 'FALSE');
                    break;
            }
        }, 0, 2);

    }

    public function getReadableDate($start = '', $end = '', $recurrDays = [])
    {
        $activeDays = [];
        foreach ($recurrDays as $day => $value) {
            if ($value == 'on') {
                $activeDays[] = $day;
            }
        }

        $today           = strtotime('now');
        $todayController = date('Ymd', $today);
        $startController = date('Ymd', strtotime($start));
        $endController   = date('Ymd', strtotime($end));
        $startString     = date('M j, Y', strtotime($start));
        $endString       = date('M j, Y', strtotime($end));

        if(date('Y', strtotime($start)) == date('Y', strtotime($end))) {
            $startString = date('M j', strtotime($start));
        }
        if(date('M', strtotime($start)) == date('M', strtotime($end))) {
            $startString = date('M j', strtotime($start));
            $endString       = date('j, Y', strtotime($end));
        }

        $dateString = '';
        if (count($activeDays) > 0) {
            $i = 1;
            foreach ($activeDays as $day) {
                $dateString .= ucfirst($day . ($day != 'weekends' && $day != 'weekdays' ? 's' : ''));
                if ($i < count($activeDays)) {
                    $dateString .= ($i == count($activeDays) - 1 ? ' and ' : ', ');
                }
                $i++;
            }
            if ($end != null && $startController != $endController) {
                $dateString .= ($startController > $todayController ? ' from ' . $startString . ' to ' : ' through ' ) . $endString;
            }
        }else{
            if ($end != null && $startController != $endController) {
                $dateString .= ($startController > $todayController ? $startString . ' - ' : ' through ' ) . $endString;
            }else{
                $dateString .= $startString;
            }
        }

        return $dateString;
    }

    public function getEvents($args, $category = '', $limit = -1)
    {

        $request = [
            'posts_per_page' => $limit,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'meta_value_num',
            'meta_key'       => 'event_details_start',
            'post_type'      => 'event',
            'post_status'    => 'publish',
        ];

        $request = array_merge($request, $args);

        if ($category != '') {
            $categoryArray        = [
                [
                    'taxonomy'         => 'event',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ],
            ];
            $request['tax_query'] = $categoryArray;
        }

        $postList = get_posts($request);

        foreach ($postList as $post) {
            $recurrDays = [
                'monday'    => (isset($post->recurring_settings_monday) ? $post->recurring_settings_monday : null),
                'tuesday'   => (isset($post->recurring_settings_tuesday) ? $post->recurring_settings_tuesday : null),
                'wednesday' => (isset($post->recurring_settings_wednesday) ? $post->recurring_settings_wednesday : null),
                'thursday'  => (isset($post->recurring_settings_thursday) ? $post->recurring_settings_thursday : null),
                'friday'    => (isset($post->recurring_settings_friday) ? $post->recurring_settings_friday : null),
                'saturday'  => (isset($post->recurring_settings_saturday) ? $post->recurring_settings_saturday : null),
                'sunday'    => (isset($post->recurring_settings_sunday) ? $post->recurring_settings_sunday : null),
                'weekends'    => (isset($post->recurring_settings_weekends) ? $post->recurring_settings_weekends : null),
                'weekdays'    => (isset($post->recurring_settings_weekdays) ? $post->recurring_settings_weekdays : null),
            ];
            $start      = (isset($post->event_details_start) ? $post->event_details_start : null);
            $end        = (isset($post->event_details_end) ? $post->event_details_end : null);

            $outputArray[] = [
                'id'              => (isset($post->ID) ? $post->ID : null),
                'name'            => (isset($post->post_title) ? $post->post_title : null),
                'slug'            => (isset($post->post_name) ? $post->post_name : null),
                'photo'           => (isset($post->event_details_photo_file) ? $post->event_details_photo_file : null),
                'start'           => $start,
                'end'             => $end,
                'recurring'       => (isset($post->recurring_settings_recurring) ? $post->recurring_settings_recurring : null),
                'recurr_on'       => $recurrDays,
                'recurr_readable' => $this->getReadableDate($start, $end, $recurrDays),
                'time'            => (isset($post->event_details_time) ? $post->event_details_time : null),
                'location'        => (isset($post->event_details_location) ? $post->event_details_location : null),
                'full_image'      => (isset($post->event_details_full_image) ? $post->event_details_full_image : null),
                'details'         => (isset($post->event_details_show_details) ? $post->event_details_show_details : null),
                'tickets'         => (isset($post->event_details_tickets_link) ? $post->event_details_tickets_link : null),
                'content'         => (isset($post->event_description_html) ? $post->event_description_html : null),
                'link'            => get_permalink($post->ID),
            ];
        }
        //echo '<pre>', print_r($outputArray), '<br>', '</pre>';

        return $outputArray;

    }

    private function getWeek($date)
    {

        $currentYear  = date('Y', strtotime($date));
        $currentMonth = date('m', strtotime($date));
        $currentWeek  = date('W', strtotime($date));

        $firstWeek = date("W", strtotime("$currentYear-$currentMonth-01"));

        if ($currentMonth == 12) {
            $currentYear++;
        } else {
            $currentMonth++;
        }

        $lastWeek = date("W", strtotime("$currentYear-$currentMonth-01") - 86400);

        $weekArr = [];
        $j       = 1;
        for ($i = $firstWeek; $i <= $lastWeek; $i++) {
            $weekArr[$i] = $j;
            $j++;
        }

        return $weekArr[$currentWeek];

    }

    private function orderEvents($inputArray)
    {

        $sorter      = [];
        $returnArray = [];
        if(is_array($inputArray)) {
            reset($inputArray);
            foreach ($inputArray as $key => $var) {
                $sorter[$key] = $var['start'];
            }
            asort($sorter);
            foreach ($sorter as $key => $var) {
                $returnArray[$key] = $inputArray[$key];
            }
        }

        return $returnArray;

    }

    protected function advanceDate($var)
    {
        $today     = date('Ymd');
        $date      = \DateTime::createFromFormat('Ymd', $var['start'], $this->timezone);
        $todayDate = \DateTime::createFromFormat('Ymd', $today, $this->timezone);
        $weekDay   = date('l', strtotime($var['start']));
        $thisDay   = date('d', strtotime($var['start']));
        $thisMonth = date('F', strtotime($var['start']));
        $thisYear  = date('Y', strtotime($var['start']));
        $newDate   = $var['start'];

        if ($var['recurring'] == 'Weekly') {
            $newDate = $todayDate->modify('next ' . $weekDay);
            $newDate = $newDate->format('Ymd');
        }
        if ($var['recurring'] == 'Monthly') {

            $week = $this->getWeek($var['start']);
            if ($week == 1) {
                $week = 'First';
            }
            if ($week == 2) {
                $week = 'Second';
            }
            if ($week == 3) {
                $week = 'Third';
            }
            if ($week == 4) {
                $week = 'Fourth';
            }
            if ($week == 5) {
                $week = 'Fifth';
            }

            $dateString = $week . ' ' . $weekDay . ' of next month';
            $newDate    = $todayDate->modify($dateString)->format('Ymd');
        }
        if ($var['recurring'] == 'Monthday') {

            $newDate = $thisYear . ((int)date('n') + 1) . $thisDay;

        }

        return $newDate;
    }

    public function getUpcomingEvents($args = [], $category = '', $limit = -1)
    {

        $today = date('Ymd');

        $metaQuery['meta_query'] = [
            'relation' => 'OR',
            [
                'key'     => 'event_details_end',
                'value'   => $today,
                'compare' => '>='
            ],
            [
                'key'     => 'event_details_recurring',
                'value'   => 'none',
                'compare' => '!='
            ]
        ];

        $metaQuery   = array_merge($metaQuery, $args);
        $outputArray = $this->getEvents($metaQuery, $category, $limit);
        if(count($outputArray) > 0) {
            foreach ($outputArray as $key => $var) {
                if ($var['start'] < $today + 1) {
                    $outputArray[$key]['start'] = $this->advanceDate($var);
                }
            }
        }

        $outputArray = $this->orderEvents($outputArray);

        return $outputArray;

    }

    public function getHomePageEvents($limit = -1)
    {

        $outputArray = $this->getUpcomingEvents([], '', -1);
        foreach ($outputArray as $key => $var) {
            if ($var['featured'] != 'on') {
                unset($outputArray[$key]);
            }
        }

        $outputArray = array_slice($outputArray, 0, $limit, false);

        return $outputArray;

    }

    public function getSingleEvent($postId)
    {
        $event = $this->getEvents([
            'include' => $postId
        ], '', 1)[0];

        $event['formatted_date'] = $this->getDates($event);

        //echo '<pre>',print_r($event),'</pre>';

        return $event;
    }

    public function getDates($event)
    {
        if ($event['start'] == $event['end']) {
            return date('l F j, Y');
        } else {

            $date      = \DateTime::createFromFormat('Ymd', $event['start'], $this->timezone);
            $todayDate = \DateTime::createFromFormat('Ymd', date('Ymd'), $this->timezone);
            $weekDay   = date('l', strtotime($event['start']));
            $thisDay   = date('d', strtotime($event['start']));
            $thisMonth = date('F', strtotime($event['start']));
            $thisYear  = date('Y', strtotime($event['start']));

            if ($event['recurring'] == 'Weekly') {
                $dateString = 'Every ' . $weekDay;
            }
            if ($event['recurring'] == 'Monthly') {

                $week = $this->getWeek($event['start']);
                if ($week == 1) {
                    $week = 'First';
                }
                if ($week == 2) {
                    $week = 'Second';
                }
                if ($week == 3) {
                    $week = 'Third';
                }
                if ($week == 4) {
                    $week = 'Fourth';
                }
                if ($week == 5) {
                    $week = 'Fifth';
                }

                $dateString = $week . ' ' . $weekDay . ' of each month';
            }
            if ($event['recurring'] == 'Monthday') {

                $dateString = 'The ' . $thisDay . ' day of each month';

            }
            if ($event['recurring'] == 'None') {

                $startMonth = date('F', strtotime($event['start']));
                $endMonth   = date('F', strtotime($event['end']));

                if ($event['start'] == $event['end'] || $event['end'] == '') {
                    $dateString = date('l F j, Y', strtotime($event['start']));
                } else {
                    if ($startMonth == $endMonth) {
                        $dateString = date('l F j', strtotime($event['start'])) . '-' . date('j, Y',
                                strtotime($event['end']));
                    } else {
                        $dateString = date('l F j, Y', strtotime($event['start'])) . ' - ' . date('l F j, Y',
                                strtotime($event['end']));
                    }
                }

            }

            return $dateString;

        }
    }
}
