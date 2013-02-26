//
//  mlViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-18.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewController.h"

@interface mlViewController ()

@end

@implementation mlViewController

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    self.autoRotate = NO;
	// Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (BOOL)shouldAutorotate
{
    return self.autoRotate;
}
- (NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationPortrait;
}
@end
