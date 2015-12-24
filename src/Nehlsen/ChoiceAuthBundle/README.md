1) Kernel:
```
new Nehlsen\ChoiceAuthBundle\NehlsenChoiceAuthBundle(),
```

2) security.yml
```
security:
  always_authenticate_before_granting: true

  providers:
    choice:
      id: choice_auth.user_provider

  firewalls:
    dev:
      pattern: ^/(_(profiler|wdt)|css|images|js)/
      security: false

    login_firewall:
      pattern:   ^/login$
      anonymous: ~

    main:
      pattern: ^/
#      entry_point: some.service...
      provider: choice
      choice_auth:
        login_path: /login
      logout:
        path:   /logout
      anonymous: ~

      remember_me:
        lifetime: 1209600 # 14 days in seconds
        always_remember_me: true
        key: foo-baz

  access_control:
    - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
#    - { path: ^/$, roles: IS_AUTHENTICATED_ANONYMOUSLY }
    - { path: ^/, roles: ROLE_USER }
```

3) routing.yml
```
nehlsen_choice_auth:
  resource: "@NehlsenChoiceAuthBundle/Resources/config/routing.yml"
  prefix:   /
```