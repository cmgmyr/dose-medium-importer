# Medium Importer Challenge

## Available Commands

    # Add a user to the system
    php artisan users:create "Chris Gmyr" MEDIUM_TOKEN
    
    # Delete a user from the system
    php artisan users:delete USER_ID
    
    # View all system users
    php artisan users:all
    
    # Get all publications for the given user
    php artisan medium:publications -U USER_ID
    php artisan medium:publications -T MEDIUM_TOKEN
    
    # Import as single user to their profile
    php artisan medium:import-to-user -U USER_ID
    
    # Import as single user to publication
    php artisan medium:import-to-user -U USER_ID -P PUBLICATION_ID
    
    # Import articles to each system user
    php artisan medium:import
    
    # Import articles to each system user to a publication
    php artisan medium:import -P PUBLICATION_ID
    
    # Import articles from the CSV/Pending table to the users
    php artisan medium:import-ids
    
    # Import articles from the CSV/Pending table to a publication
    php artisan medium:import-ids -P PUBLICATION_ID
    
    # Verify that all Medium Tokens in the system are valid
    php artisan medium:verify-tokens

## Setup

    # Clone repo
    $ git clone REPO_URL medium && cd medium
    
    # Copy .env example
    $ cp .env.example .env
    # Update API_URL to point to the API's base URL
    
    # Install Dependencies
    $ composer install
    
    # Create & migrate DB
    $ touch data/medium.sqlite
    $ php artisan migrate
    
    # Optional user csv import
    # 1. Add data/users.csv with the format of: Name, Token (with header row)
    # 2. Run:
    $ php artisan db:seed
    
    # Optional article id import
    # 1. Add data/ids.csv with the format of: Article_id (with header row)
    # 2. Run:
    $ php artisan db:seed

## Suggested Process

1. Add all users to system via `medium:user` command, or use the `.csv` import option via database seeder
2. Verify that all users are within system via `medium:users` command
3. Get publication id from `medium:publications` command with the given user id or token
4. Import articles based on any of the `medium:import` command options

## Todo:

- [x] ~~Handle Tags~~ (use categories instead)
- [x] Handle Categories?
- [x] Handle mapping internal users to Medium users, post as them
- [x] Tracking previously imported articles so we don't re-import them
- [x] ~~Ask to post to individual account vs publication~~
- [x] Handle error output
- [ ] Cleanup & Refactoring
- [x] Use CSV for users seeder
- [x] Import articles by CSV file
- [ ] Better handle authentication and blacklisted performance
