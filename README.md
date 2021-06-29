![Two Factor Authentication](https://user-images.githubusercontent.com/13532448/123786493-5f295c80-d8f7-11eb-955b-fb4d083ceb65.png)

[![Latest Stable Version](http://poser.pugx.org/juashyam/authenticator/v)](https://packagist.org/packages/juashyam/authenticator)
[![Total Downloads](http://poser.pugx.org/juashyam/authenticator/downloads)](https://packagist.org/packages/juashyam/authenticator)
[![License](http://poser.pugx.org/juashyam/authenticator/license)](https://packagist.org/packages/juashyam/authenticator)

Magento Two-Factor Authentication, which uses Google authenticator and smart phone to authenticate Admin session

## Getting Started

Magento 2 Two-Factor Authentication will protect our Magento store from insecure Internet connections.

Every time we login there is a chance that someone is sniffing or logging the password, which can later be used to login to your store.

You can protect our store from these type of attacks by using our advanced Magento Two-Factor Authentication, which uses Google authenticator and your smart phone in order to authenticate your admin session.

### Prerequisites
[Google Authenticator App](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en)

### Installation

```
composer require juashyam/authenticator
php bin/magento module:enable Neyamtux_Authenticator
php bin/magento setup:upgrade
```

Please install & enable [Elgentos_Frontend2FA](https://github.com/elgentos/frontend2fa) for frontend 2FA.

```
composer require elgentos/frontend2fa
php bin/magento module:enable Elgentos_Frontend2FA
php bin/magento setup:upgrade
```

## Fun Demo
It authenticates only Admin Login however there is a frontend demo as well.

{Magento Store URL}/authenticator

![Demo](https://image.prntscr.com/image/gSZmYoEgRRyAu_djujkAYQ.png)

## Authors

* **Shyam Kumar**

## License

This project is licensed under the MIT License


[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bhttps%3A%2F%2Fgithub.com%2Fneyamtux%2F2-Factor-Authentication.svg?type=large)](https://app.fossa.io/projects/git%2Bhttps%3A%2F%2Fgithub.com%2Fneyamtux%2F2-Factor-Authentication?ref=badge_large)
