//
//  interactiveViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "interactiveViewController.h"
#import "toolIosSize.h"

@interface interactiveViewController ()

@property (retain , nonatomic) UIView *contentView;
@property (retain , nonatomic) photoCommentViewController *cmtC;
@property (retain , nonatomic) photoLikeViewController *likeC;

@end

@implementation interactiveViewController

@synthesize which;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        

    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];

    [self viewDidAppear:YES];
}

- (void)viewDidAppear:(BOOL)animated
{

    UIToolbar *whichBar = [[UIToolbar alloc] initWithFrame:CGRectMake(0, 0, 320, 30)];
    UISegmentedControl *whichSeg = [[UISegmentedControl alloc] initWithItems:[NSArray arrayWithObjects:@"aaa" , @"bbb", nil]];
    whichSeg.frame = CGRectMake(60, 2, 200, 26);
    [whichSeg addTarget:self action:@selector(changeWhichBySeg:) forControlEvents:UIControlEventValueChanged];
    [whichBar addSubview:whichSeg];
    [self.view addSubview:whichBar];
    [self changeWhich:1];

}
     
- (void)changeWhichBySeg:(UISegmentedControl *)seg
{
    [self changeWhich:seg.selectedSegmentIndex];
}
- (void)changeWhichByTimer:(NSTimer *)timer
{
    int i = (int)[timer userInfo];
    [self changeWhich:i];
}

- (void)changeWhich:(int)which
{
    if (self.contentView != nil) {
        [self.contentView removeFromSuperview];
        self.contentView = nil;
    }
    if (which == 0) {
        if (self.cmtC == nil) {
            self.cmtC = [[photoCommentViewController alloc] init];
        }

        self.contentView = self.cmtC.view;
    } else {
        if (self.likeC == nil) {
            self.likeC = [[photoLikeViewController alloc] init];
        }
        self.contentView = self.likeC.view;
    }
    self.contentView.frame = CGRectMake(0, 30, 320, [toolIosSize heightWithoutNav]-30);
    [self.view addSubview:self.contentView];
}


- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
