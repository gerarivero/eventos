<?php if (!class_exists('CaptchaConfiguration')) { return; }

// BotDetect PHP Captcha configuration options

return [
  // Captcha configuration for example page
  'ExampleCaptcha' => [
    'UserInputID' => 'captchaCode',
    'ImageWidth' => 250,
    'ImageHeight' => 100,
    'Locale' => 'es-MX',
    'SoundRegenerationMode' => 'unlimited'
  ],

];
