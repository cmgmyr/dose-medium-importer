# Medium Importer Challenge

## Available Commands

    # Add a user to the system
    php artisan medium:user "Chris Gmyr" MEDIUM_TOKEN
    
    # View all system users
    php artisan medium:users
    
    # Get all publications for the given user
    php artisan medium:publications -U USER_ID
    php artisan medium:publications -T MEDIUM_TOKEN
    
    # Import as single user to their profile
    php artisan medium:import -U USER_ID
    
    # Import as single user to publication
    php artisan medium:import -U USER_ID -P PUBLICATION_ID

## Suggested Process

1. Add all users to system via `medium:user` command
2. Verify that all users are within system via `medium:users` command
3. Get publication id from `medium:publications` command with the given user id or token
4. Import articles based on any of the `medium:import` command options

## Todo:

- [ ] ~~Handle Tags~~ (use categories instead)
- [x] Handle Categories?
- [ ] Handle mapping internal users to Medium users, post as them
- [ ] Tracking previously imported articles so we don't re-import them
- [ ] Ask to post to individual account vs publication
- [x] Handle error output
