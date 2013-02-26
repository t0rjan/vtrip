//
//  snapViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-5.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "snapViewController.h"

@interface snapViewController ()

@end

@implementation snapViewController

@synthesize cameraController;
@synthesize is;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    self.is = NO;
    return self;
}

- (void)viewDidLoad
{

    [super viewDidLoad];
    [self viewDidAppear:YES];
}
- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    self.cameraController = [[cameraViewController alloc] init];


    if(!self.is)
    {
        [self.navigationController presentViewController:self.cameraController animated:YES completion:^{
            
        }];
        self.is = YES;
    }
}
- (void)viewDidDisappear:(BOOL)animated
{
    NSLog(@"www");
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
