//
//  cameraViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-5.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "cameraViewController.h"

@interface cameraViewController ()

@end

@implementation cameraViewController

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
    // Do any additional setup after loading the view from its nib.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)backBtn:(id)sender
{
    [self dismissViewControllerAnimated:YES completion:^{}];
}
@end
