//
//  positionInfoViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-23.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "positionInfoViewController.h"

@interface positionInfoViewController ()

@end

@implementation positionInfoViewController

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
    UILabel *titleLb = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 320, 30)];
    titleLb.text = @"position";
    [self.view addSubview:titleLb];
    
    UIButton *btn = [UIButton buttonWithType:UIButtonTypeRoundedRect];
    btn.frame = CGRectMake(0, 30, 100, 30);
    btn.backgroundColor = [UIColor blueColor];
    [btn addTarget:self action:@selector(dismiss) forControlEvents:UIControlEventTouchUpInside];
    [self.view addSubview:btn];

}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
- (void)dismiss
{
    [self dismissViewControllerAnimated:YES completion:nil];
}
@end
