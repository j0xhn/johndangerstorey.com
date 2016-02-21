<?php include 'header.html'; ?>
    <!-- masthead -->
    <div class="jumbotron">
      <h2 class="jumbotron--title">Twiz</h2>
      <p class="jumbotron--sub-title">Parse <span class="icon-star"></span> Objective C  <span class="icon-star"></span> Design</p>
      <a href="https://itunes.apple.com/WebObjects/MZStore.woa/wa/viewSoftware?id=907314308&mt=8" class="btn  btn--green">Download</a>
    </div><!-- /masthead -->

        <!-- main-content -->
        <div class="container  main-content">

          <div class="row">

            <!-- left content -->
            <div class="col-lg-8  blog">

              <!-- blog post -->
              <img src="img/TwizBig.png" class="blog-main-img  img-responsive" alt="blog main image" />

              <h4 class="blog-post-title">Twitter Quiz = Twiz</h4>  
                <ul class="list-unstyled  tags  blog-tags">
                  <li><a href="https://itunes.apple.com/WebObjects/MZStore.woa/wa/viewSoftware?id=907314308&mt=8">Download</a></li>
                </ul>
              <h6 class="blog-post-sub-title">I built this app for App Raptors (a client) while attending Dev Mountain.  It gets your twitter feed and quizes you on who said what rewarding points for correct answers.  Even though I personally believe Javascript will devour the world wide web... it doesn't hurt to put a little native experience in your toolbelt right? 
              <br>
              <br>
              I did it all.  From design to development, this was my baby.  I didn't handle authentication of users or create my own database and api -- big shout out to Parse for that stuff -- but I did get to learn quite a bit about the exciting world of native iOS.
              <br>
              <br>
              </h6>

              <blockquote>
                "any application that can be written in JavaScript, will eventually be written in JavaScript"
                <small>Atwood's Law</small>
              </blockquote>

              <h6 class="blog-post-sub-title">Wanted to mention I haven't touched an iPhone app since graduating because I handed it off to the firm I built it for. I had help along the way from mentors were great in helping me pass roadblocks, but like the quote above, I just think javascript and web based API are going to be more usefull to me over time.  That being said and I worked extensively with the Parse and Twitter API while including my own game logic in a way that the user can just keep playing seemelessly without ever thinking twice.  KEEP IN MIND: This was my first iPhone app, still relatively new to development.  I don't profess this to be clean or awesome code... but it did work :)</h6>

<pre>

#pragma mark - Answers and Score

-(void) generateRandomNumberArray {
    int objectsInArray = [self.possibleAnswerBucketArray count]; // makes it so you don't get numbers higher than what is currently in there

    NSInteger randomNumber = (NSInteger) arc4random_uniform(objectsInArray); // picks between 0 and n-1
    if (self.mutableArrayContainingNumbers) {
        if ([self.mutableArrayContainingNumbers containsObject: [NSNumber numberWithInteger:randomNumber]]){ // crashes here when you don't have more than 4 possible answers to choose from
            [self generateRandomNumberArray]; // call the method again and get a new object
        } else {
            // end case, it doesn't contain it so you have a number you can use
            [self.mutableArrayContainingNumbers addObject: [NSNumber numberWithInteger:randomNumber]];
            if ([self.mutableArrayContainingNumbers count] != 3) {
                [self generateRandomNumberArray];
            }
        }

    } else { // runs the first time
        NSMutableArray *mutableArrayContainingNumbers = [NSMutableArray new];
        [mutableArrayContainingNumbers addObject: [NSNumber numberWithInteger:randomNumber]];
        self.mutableArrayContainingNumbers = mutableArrayContainingNumbers;
        [self generateRandomNumberArray];
    }
    
}

- (NSMutableArray *) requestActivePossibleAnswers:(MyActiveTweet *)activeTweet{
    
    [self generateRandomNumberArray];
    
    NSMutableArray *activePossibleAnswers = [NSMutableArray new];
    for (int i = 0; i < [self.mutableArrayContainingNumbers count]; i++)
    {
        NSNumber *possibleAnswerRandomNumber = self.mutableArrayContainingNumbers[i];
        // if error's here it's because you don't have any possibleAnswers
        NSDictionary *possibleAnswer = [self.possibleAnswerBucketArray objectAtIndex:possibleAnswerRandomNumber.integerValue];
        [activePossibleAnswers addObject:possibleAnswer];
    }

    self.mutableArrayContainingNumbers = nil;
    NSNumber *points = [NSNumber numberWithInteger:5];
    NSDictionary *correctAnswer = [[NSDictionary alloc]initWithObjectsAndKeys:
                                   activeTweet.correctAnswerPhotoURL, possibleAnswerPhotoURLKey,
                                   activeTweet.correctAnswerID,possibleAnswerAuthorKey,
                                   points, tweetPointsKey, nil];

    for (NSDictionary *answer in activePossibleAnswers) {
        if ([answer[possibleAnswerAuthorKey] isEqualToString:correctAnswer[possibleAnswerAuthorKey]]) {
            NSLog(@"this answer is a duplicate");
            return [self requestActivePossibleAnswers:activeTweet];
        }
    }
    
    NSInteger randomIndexNumber = (NSInteger) arc4random_uniform(4); // pics random number n-1
    [activePossibleAnswers insertObject:correctAnswer atIndex:randomIndexNumber];
    
    return activePossibleAnswers;
}

</pre>

            </div><!-- /left content -->

              <!-- right content -->
              <div class="col-lg-4  right-hand-bar">

                <hr class="hidden-lg">

                  <h5 class='no-top-margin'>Featurettes</h5>

                  <ul class="list-unstyled  tags  category-tags">
                    <li><a>Coded while at Dev Mountain</a></li>
                    <li><a>Twitter Login & API</a></li>
                    <li><a>Endured Apple Certificate Headaches</a></li>
                    <li><a>Game Logic</a></li>
                    <li><a>Design</a></li>
                    <li><a>Animations</a></li>
                    <li><a>Objective C</a></li>
                  </ul>

              </div><!-- /right content -->

          </div>

        </div><!-- /main-content -->
<?php include 'footer.html'; ?>