//
//  modelBaseViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-13.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "modelBaseViewController.h"

@interface modelBaseViewController ()

@property (strong , nonatomic) UILabel *topBarTitle;

- (void)dismissModel;

@end

@implementation modelBaseViewController

@synthesize pageTitle;
@synthesize topBarTitle;


- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        
        if(!pageTitle)
            pageTitle = @"啊啊啊？";
        
        UIView *topBar = [[UIView alloc] initWithFrame:CGRectMake(0, 0, [toolIosSize widthScreen], 30)];
        topBar.backgroundColor = [UIColor lightGrayColor];
        
        //title
        self.topBarTitle = [[UILabel alloc] initWithFrame:CGRectMake(40, 0, 180, 26)];
        self.topBarTitle.backgroundColor = [UIColor clearColor];
        self.topBarTitle.text = pageTitle;
        self.topBarTitle.textAlignment = UITextAlignmentCenter;
        [topBar addSubview:topBarTitle];
        //close btn
        UILabel *closeLb = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 40, 26)];
        closeLb.text = @"关闭";
        closeLb.textColor = [UIColor whiteColor];
        closeLb.backgroundColor = [UIColor darkGrayColor];
        UIButton *closeBtn = [[UIButton alloc] initWithFrame:CGRectMake(270, 0, 40, 26)];
        closeBtn.backgroundColor = [UIColor blueColor];
        [closeBtn addTarget:self action:@selector(dismissModel) forControlEvents:UIControlEventTouchUpInside];
        [closeBtn addSubview:closeLb];
        [topBar addSubview:closeBtn];
        
        

        [self.view addSubview:topBar];
        
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)setPageTitle:(NSString *)pageTitle
{
    self.topBarTitle.text = pageTitle;
}

- (void)dismissModel
{
    NSLog(@"xxx");
    [self dismissViewControllerAnimated:YES completion:nil];
}

@end
