//
//  editTripViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-3-12.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "editTripViewController.h"

@interface editTripViewController ()

@end

@implementation editTripViewController
@synthesize tripInfo;

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
    [super setPageTitle:@"木林"];
    UIToolbar *toolBar = [[UIToolbar alloc] initWithFrame:CGRectMake(0, [toolIosSize heightWithoutToolBar], [toolIosSize widthScreen], iosSizeToolHeight)];
    
    UIButton *submitBtn = [UIButton buttonWithType:UIButtonTypeCustom];
    submitBtn.frame = CGRectMake(100, 3, 200, 30);
    submitBtn.backgroundColor = [UIColor greenColor];
    [submitBtn addTarget:self action:@selector(save) forControlEvents:UIControlEventTouchUpInside];
    UILabel *submitLb = [[UILabel alloc] initWithFrame:CGRectMake(10, 1, 150, 30)];
    submitLb.text = @"保存修改";
    submitLb.backgroundColor = [UIColor clearColor];
    [submitBtn addSubview:submitLb];
    
    UIButton *cancelBtn = [UIButton buttonWithType:UIButtonTypeCustom];
    cancelBtn.frame = CGRectMake(20, 3, 40, 30);
    cancelBtn.backgroundColor = [UIColor redColor];
    UILabel *cancelLb = [[UILabel alloc] initWithFrame:CGRectMake(10, 1, 150, 30)];
    cancelLb.text = @"取消";
    cancelLb.backgroundColor = [UIColor clearColor];
    [cancelBtn addSubview:cancelLb];
    
    [toolBar addSubview:submitBtn];
    [toolBar addSubview:cancelBtn];
    
    [self.view addSubview:toolBar];
    
    
    [self viewWillAppear:YES];
    
}

- (void)viewWillAppear:(BOOL)animated
{
    if(self.tripInfo != nil)
    {
        self.tripTitle.text = [self.tripInfo objectForKey:@"title"];
        self.tripDays.text = [self.tripInfo objectForKey:@"days"];
        self.tripDate.text = [self.tripInfo objectForKey:@"start_date"];
        [self.daysPicker selectRow:[[self.tripInfo objectForKey:@"days"] intValue]-1 inComponent:0 animated:NO];
        NSDate *date = [toolTime str2nsdate:[self.tripInfo objectForKey:@"start_date"]];
        [self.datePicker setDate: date];
    }
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
