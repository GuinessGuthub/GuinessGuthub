<?php

// URL of the Telegram channel
$url = 'https://t.me/s/adarshmpro';

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session and get the HTML response
$html = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)], JSON_PRETTY_PRINT);
    exit;
}

// Close cURL session
curl_close($ch);

// Load the HTML into a DOMDocument
$dom = new DOMDocument;
$dom->loadHTML($html);

// Create an XPath object to navigate the DOM
$xpath = new DOMXPath($dom);

// Extract information from the HTML using XPath
$name = $xpath->query('//div[@class="tgme_widget_message_owner_name"]')->item(0)->nodeValue;
$message = $xpath->query('//div[@class="tgme_widget_message_text"]//a')->item(0)->getAttribute('href');
$views = $xpath->query('//span[@class="tgme_widget_message_views"]')->item(0)->nodeValue;
$author = $xpath->query('//span[@class="tgme_widget_message_from_author"]')->item(0)->nodeValue;
$linkImg = $xpath->query('//i[@class="link_preview_right_image"]')->item(0)->getAttribute('style');
$linkTitle = $xpath->query('//div[@class="link_preview_title"]')->item(0)->nodeValue;
$linkDescription = $xpath->query('//div[@class="link_preview_site_name"]')->item(0)->nodeValue;

// Extracting image URL from style attribute
preg_match('/background-image:url\(\'(.*?)\'\)/', $linkImg, $matches);
$linkImgUrl = isset($matches[1]) ? $matches[1] : '';

// Create JSON response
$jsonResponse = [
    'name' => $name,
    'message' => $message,
    'views' => $views,
    'author' => $author,
    'linkimg' => $linkImgUrl,
    'linktitle' => $linkTitle,
    'linkdescription' => $linkDescription,
];

// Output JSON
echo json_encode($jsonResponse, JSON_PRETTY_PRINT);
