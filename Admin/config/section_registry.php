<?php
return [
    'home' => ['label' => 'Home', 'sections' => [
        'story' => ['label' => 'About Us', 'fields' => ['heading', 'subheading', 'body', 'image', 'image2', 'check1', 'check2', 'list1', 'list2', 'button_text', 'button_link']],
        'services' => ['label' => 'Services', 'edit_url' => 'service-cards.php'],
    ]],
    'about' => ['label' => 'About Us', 'sections' => [
        'story' => ['label' => 'Discover Our Story', 'fields' => ['heading', 'subheading', 'body', 'image', 'image2']],
        'story_tabs' => ['label' => 'Story Tabs (Mission / Vission / Goal)', 'edit_url' => 'about-story-tabs-edit.php'],
        'team' => ['label' => 'Meet Our Team', 'edit_url' => 'team.php'],
        'video' => ['label' => 'Video Banner', 'fields' => ['heading', 'image']],
        'testimonials' => ['label' => 'Client Testimonials', 'edit_url' => 'testimonials.php'],
        'client_logos' => ['label' => 'Trusted Client Logos', 'edit_url' => 'client-logos.php'],
    ]],
    'contact' => ['label' => 'Contact Us', 'sections' => [
        'intro' => ['label' => 'Page Intro', 'fields' => ['heading', 'subheading', 'body']],
        'help' => ['label' => 'We\'re Here To Help', 'fields' => ['heading', 'subheading', 'body', 'image', 'button_text', 'button_link']],
        'faqs' => ['label' => 'Common Questions (FAQ)', 'edit_url' => 'contact-faqs.php'],
    ]],
    'property-management' => ['label' => 'Property Management', 'sections' => [
        'intro' => ['label' => 'Service Overview', 'fields' => ['heading', 'subheading', 'body', 'image', 'image2', 'check1', 'check2', 'button_text', 'button_link']],
        'handles' => ['label' => 'What We Handle', 'edit_url' => 'pm-handles.php'],
        'stats' => ['label' => 'Why Owners Choose Us', 'edit_url' => 'pm-stats.php'],
        'faqs' => ['label' => 'Common Questions (FAQ)', 'edit_url' => 'pm-faqs.php'],
    ]],
    'construction' => ['label' => 'General Construction', 'sections' => [
        'intro' => ['label' => 'Service Overview', 'fields' => ['heading', 'subheading', 'body', 'image', 'image2', 'check1', 'check2', 'button_text', 'button_link']],
        'handles' => ['label' => 'What We Handle', 'edit_url' => 'construction-handles.php'],
        'stats' => ['label' => 'Project Track Record', 'edit_url' => 'construction-stats.php'],
        'faqs' => ['label' => 'Common Questions (FAQ)', 'edit_url' => 'construction-faqs.php'],
    ]],
];
