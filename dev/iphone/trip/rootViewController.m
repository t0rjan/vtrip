//
//  rootViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-1-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "rootViewController.h"

@interface rootViewController ()

@property (strong , nonatomic) snapViewController *snap;

@end

@implementation rootViewController

@synthesize myController;
@synthesize opController;
@synthesize snapController;
@synthesize snsController;
@synthesize setController;
@synthesize snap;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        self.myController = [[UINavigationController alloc] init];
        myController.title = @"我的游记";
        myTripsViewController *mytripC = [[myTripsViewController alloc] init];
        mytripC.title = @"我的游记";


        [myController addChildViewController:mytripC];
        [[UINavigationBar appearance] setBackgroundImage:[UIImage imageNamed:@"nav_bg.png"] forBarMetrics:UIBarMetricsDefault];
        [[UITabBar appearance] setBackgroundImage:[UIImage imageNamed:@"tabbar_bg.png"]];

/*
 
        [[UIBarButtonItem appearance] setBackButtonBackgroundImage:[UIImage imageNamed:@"navbackbtn_bg.png"] forState:UIControlStateNormal barMetrics:UIBarMetricsDefault];
        [[UITabBar appearance] setBackgroundImage:[UIImage imageNamed:@"tabBg.png"]];
        [[UITabBar appearance] setBackgroundColor:[UIColor clearColor]];
*/
        
        self.snapController = [[UINavigationController alloc] init];
        snapController.title = @"走你";

        self.snap = [[snapViewController alloc] init];
        [snapController addChildViewController:self.snap];
        
        
        self.opController = [[UINavigationController alloc] init];
        opController.title = @"更多精彩";

        
        NSArray *tabList = [[NSArray alloc] initWithObjects:myController,snapController,opController , nil];


        self.viewControllers = tabList;        
        //self.selectedViewController = snapController;
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];

    
//    [tabList release];
}
- (void)viewDidAppear:(BOOL)animated
{
    self.snap.is = NO;
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
