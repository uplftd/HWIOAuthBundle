<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @final since 1.4
 */
class Office365ResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * @var array
     */
    protected $paths = [
        'identifier' => 'id',
        'email' => 'mail',
        'realname' => 'displayName',
        'firstname' => 'givenName',
        'lastname' => 'surname',
    ];

    public function getAuthorizationUrl($redirectUri, array $extraParameters = [])
    {
        $url = parent::getAuthorizationUrl($redirectUri, array_merge([
            'prompt' => $this->options['prompt'],
        ], $extraParameters));

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(Request $request, $redirectUri, array $extraParameters = [])
    {
        $extraParameters = array_merge([
            'resource' => 'https://graph.microsoft.com',
        ], $extraParameters);

        return parent::getAccessToken($request, $redirectUri, $extraParameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'authorization_url' => 'https://login.microsoftonline.com/common/oauth2/authorize',
            'access_token_url' => 'https://login.microsoftonline.com/common/oauth2/token',
            'infos_url' => 'https://graph.microsoft.com/v1.0/me',
            'prompt' => null,
        ]);

        $resolver
            // sometimes we need to force for approval prompt
            ->setAllowedValues('prompt', ['login', 'none', 'consent', 'select_account', null])
        ;
    }
}
