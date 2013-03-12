//
//  newTripViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-3-9.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "newTripViewController.h"

@interface newTripViewController ()

@property (assign , nonatomic) modelMyTrip *modelMyTrip;

@end

@implementation newTripViewController

@synthesize modelMyTrip;

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
    [super setPageTitle:@"开始新的旅行"];

    UIToolbar *toolBar = [[UIToolbar alloc] initWithFrame:CGRectMake(0, [toolIosSize heightWithoutToolBar], [toolIosSize widthScreen], iosSizeToolHeight)];
    
    UIButton *submitBtn = [UIButton buttonWithType:UIButtonTypeCustom];
    submitBtn.frame = CGRectMake(100, 3, 200, 30);
    submitBtn.backgroundColor = [UIColor greenColor];
    [submitBtn addTarget:self action:@selector(save) forControlEvents:UIControlEventTouchUpInside];
    UILabel *submitLb = [[UILabel alloc] initWithFrame:CGRectMake(10, 1, 150, 30)];
    submitLb.text = @"出发吧！";
    submitLb.backgroundColor = [UIColor clearColor];
    [submitBtn addSubview:submitLb];
    
    
    [toolBar addSubview:submitBtn];
    
    [self.view addSubview:toolBar];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma action
- (void)save
{
    if (self.modelMyTrip == nil) {
        self.modelMyTrip = [[modelMyTrip alloc] init];
    }
    if([self isAllInputAvailable])
    {
        modelUserBase *userModel = [[modelUserBase alloc] init];
        BOOL rs = [self.modelMyTrip addTripNamed:self.tripTitle.text who:[userModel getUid] startAt:self.tripDate.text forDays:[self.tripDays.text intValue] toDestination:self.tripWhere.text];
        if(rs)
        {
            mlViewCustomNotice *notice = [[mlViewCustomNotice alloc] init];
            UILabel *sucLb = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 200, 50)];
            sucLb.text = @"创建旅行成功！";
            
            [notice addSubview:sucLb];
            [notice showInView:self.view forSecond:1];
            [self dismissViewControllerAnimated:YES completion:^{
                [[NSNotificationCenter defaultCenter] postNotificationName:@"reloadData" object:nil];
            }];
        }
        else
        {
            
        }
    }
}

@end
