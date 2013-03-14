//
//  myTripsViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-1-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "myTripsViewController.h"

@interface myTripsViewController ()

@property (assign , nonatomic) int tripNowStatus;
@property (assign , nonatomic) NSMutableDictionary *tripNowInfo;
@property (assign , nonatomic) int page;
@property (retain , nonatomic) mlViewEleTableLoadingInset *tbHeader;
@property (assign , nonatomic) UIView *myIndex;
@property (retain , nonatomic) receiveCommentViewController *inboxC;
@property (assign , nonatomic) newTripViewController *newTripC;
@property (assign , nonatomic) editTripViewController *editTripC;
@property (assign , nonatomic) UIView *tableHeader;
@property (assign , nonatomic) UIButton *newTripBtn;

@end

@implementation myTripsViewController

@synthesize tripShowC;
@synthesize modelMyTrip;
@synthesize tripList;
@synthesize tripNowStatus;
@synthesize tripNowInfo;
@synthesize tableHeader;
@synthesize newTripBtn;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        //nav map btn
        UIBarButtonItem *settingBtn = [[UIBarButtonItem alloc] init];
        [settingBtn setBackgroundImage:[UIImage imageNamed:@"null.png"] forState:UIControlStateNormal barMetrics:UIBarMetricsDefault];
        [settingBtn setImage:[UIImage imageNamed:@"navIcon_setting.png"]];
        settingBtn.target = self;
        settingBtn.action = @selector(showSetting);


        self.navigationItem.leftBarButtonItem = settingBtn;

    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    self.view.backgroundColor = [UIColor colorWithPatternImage:[UIImage imageNamed:@"page_bg.png"]];
    [self renderMyIndex];
    self.tbHeader = [[mlViewEleTableLoadingInset alloc] init];
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reloadData) name:@"reloadData" object:nil];
    
    self.tb = [[UITableView alloc] initWithFrame:CGRectMake(0, 0, 320, 360)];
    [self.tb addSubview:self.tbHeader];
    self.tb.delegate = self;
    self.tb.dataSource = self;
    self.tb.backgroundColor = [UIColor clearColor];
    self.tb.separatorStyle = UITableViewCellSeparatorStyleNone;
    [self.view addSubview:self.tb];
    
    UIView *tableFooter = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 320, 30)];
    tableFooter.backgroundColor = [UIColor clearColor];
    self.tb.tableFooterView = tableFooter;
    
    self.tripNowStatus = 0;
    
    self.tableHeader = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 320, 55)];
    self.tb.tableHeaderView = self.tableHeader;

    [self reloadData];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


#pragma action
- (void)reloadData
{
    [self insertContentInset];

    if (self.modelMyTrip == nil) {
        self.modelMyTrip = [[modelMyTrip alloc] init];
    }
    self.page = 1;
    [self.modelMyTrip fetchMyTripList:1 page:1];
    NSDictionary *rs = [self.modelMyTrip getDataDictionary];
    self.tripList = [rs objectForKey:@"rows"];

    self.tripNowStatus = 0;
    self.tripNowInfo = nil;
    for (NSDictionary *d in self.tripList) {
        NSString *status = [d objectForKey:@"status"];
        if ([status isEqualToString:@"0"]) {
            self.tripNowStatus = 1;
            self.tripNowInfo = (NSMutableDictionary *)d;
        }
    }
    [self clearContentInset];
    [self renderNewTripBtn];
    [self.tb reloadData];
}
- (void)loadMoreData
{
    self.page += 1;
    [self.modelMyTrip fetchMyTripList:1 page:self.page];
    NSDictionary *rs = [self.modelMyTrip getDataDictionary];
    self.tripList = [self.tripList arrayByAddingObjectsFromArray:[rs objectForKey:@"rows"]];

    [self clearContentInset];
    [self.tb reloadData];

}
- (void)showNewTrip
{
    if (self.tripNowStatus == 1) {
        if (self.editTripC == nil) {
            self.editTripC = [[editTripViewController alloc] init];
        }
 
        [self.editTripC setTripInfo:self.tripNowInfo];
        [self presentViewController:self.editTripC animated:YES completion:nil];
   
    } else {
        if (self.newTripC == nil) {
            self.newTripC = [[newTripViewController alloc] init];
        }
        [self presentViewController:self.newTripC animated:YES completion:nil];
    }
    

    
}
- (void)showSetting
{
    static BOOL isShowing = NO;
    
    
    if (!isShowing) {
        [self.view addSubview:self.myIndex];
        isShowing = YES;
        [UIView animateWithDuration:0.3 animations:^{
            self.myIndex.transform = CGAffineTransformTranslate(self.myIndex.transform, 0, [toolIosSize heightWithoutNavAndTab]);
        }];
        
    } else {
        isShowing = NO;
        [UIView animateWithDuration:0.3 animations:^{
            self.myIndex.transform = CGAffineTransformTranslate(self.myIndex.transform, 0, 0-[toolIosSize heightWithoutNavAndTab]);
        } completion:^(BOOL comp){
            [self.myIndex removeFromSuperview];
        }];
    }
    
    //self.view.hidden = YES;
}
- (void)showInbox
{
    if (self.inboxC == nil) {
        self.inboxC = [[receiveCommentViewController alloc] init];
    }
    [self showSetting];
    [self.navigationController pushViewController:self.inboxC animated:YES];
}


#pragma view
- (void)clearContentInset
{
    [UIView animateWithDuration:0.3 animations:^{
        self.tb.contentInset = UIEdgeInsetsMake(0, 0, 0, 0);
    }];
    
}
- (void)insertContentInset
{
    if (self.tb.contentInset.top == 0) {
        [UIView animateWithDuration:0.3 animations:^{
            self.tb.contentInset = UIEdgeInsetsMake(60, 0, 0, 0);
        }];
    }
}
- (void)renderNewTripBtn
{
    if (self.newTripBtn != nil) {
        [self.newTripBtn removeFromSuperview];
        self.newTripBtn = nil;
        
    }
    self.newTripBtn = [[UIButton alloc] initWithFrame:CGRectMake(30, 10, 260, 35)];
    [self.newTripBtn addTarget:self action:@selector(showNewTrip) forControlEvents:UIControlEventTouchUpInside];
    self.newTripBtn.backgroundColor = [UIColor clearColor];
    self.newTripBtn.layer.cornerRadius = 6;
    
    
    if (self.tripNowStatus == 0) {
        [self.newTripBtn setImage:[UIImage imageNamed:@"trip_add.png"] forState:UIControlStateNormal];
        [self.newTripBtn setImage:[UIImage imageNamed:@"trip_add_2.png"] forState:UIControlStateHighlighted];
    }
    else if (self.tripNowStatus == 1)
    {
        UILabel *btnLb = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 100, 30)];
        NSString *title = [self.tripNowInfo objectForKey:@"title"];
        btnLb.text = [NSString stringWithFormat:@"下一站：%@",title];
        [self.newTripBtn addSubview:btnLb];
    }
    [self.tableHeader addSubview:self.newTripBtn];
}
- (void)renderMyIndex
{
    self.myIndex = [[UIView alloc] initWithFrame:CGRectMake(0, 0-[toolIosSize heightWithoutNavAndTab], 320, [toolIosSize heightWithoutNavAndTab])];
    self.myIndex.backgroundColor = [UIColor yellowColor];
    
    UIButton *btn = [UIButton buttonWithType:UIButtonTypeRoundedRect];
    btn.frame = CGRectMake(5, 5, 310, 30);
    btn.backgroundColor = [UIColor blueColor];
    [btn addTarget:self action:@selector(showInbox) forControlEvents:UIControlEventTouchUpInside];
    [self.myIndex addSubview:btn];
    
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [self.tripList count];
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{

    static NSString *cellId = @"cellId";
    
    static BOOL nibsRegistered = NO;
    if(!nibsRegistered)
    {
        UINib *nib = [UINib nibWithNibName:@"tripItem" bundle:nil];
        [tableView registerNib:nib forCellReuseIdentifier:cellId];
        nibsRegistered = YES;
    }
    
    tripItem *cell = [tableView dequeueReusableCellWithIdentifier:cellId];
    
    if([indexPath row] == 0)
    {
        
    }
    if ([self.tripList count] < [indexPath row]) {
        return cell;
    }
    NSDictionary *secDic = [self.tripList objectAtIndex:[indexPath row]];
    cell.title = [secDic objectForKey:@"title"];
    cell.time = [secDic objectForKey:@"start_date"];
    cell.bgimg = [secDic objectForKey:@"trpLsCvr_url"];
    
    [cell render];
    
    return cell;
    
    
    
}
- (CGFloat) tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return 70;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if(tripShowC == nil)
    {
        self.tripShowC = [[tripShowViewController alloc] init];
    }
    NSDictionary *secDic = [self.tripList objectAtIndex:[indexPath row]];
    self.tripShowC.tripId = [[secDic objectForKey:@"id"] intValue];

    self.tripShowC.tripInfo = secDic;
    self.tripShowC.title = [secDic objectForKey:@"title"];
    [self.tripShowC viewDidAppear:YES];
    self.tripShowC.hidesBottomBarWhenPushed = YES;
    [self.navigationController pushViewController:tripShowC animated:YES];
}

- (void)scrollViewWillEndDragging:(UIScrollView *)scrollView withVelocity:(CGPoint)velocity targetContentOffset:(inout CGPoint *)targetContentOffset
{
    if(scrollView.contentOffset.y < -60)
    {
        self.tb.contentInset = UIEdgeInsetsMake(60, 0, 0, 0);
//        NSTimer *timer = [NSTimer scheduledTimerWithTimeInterval:(NSTimeInterval)0.1 target:self selector:@selector(reloadData) userInfo:nil repeats:NO];
        [self reloadData];
    }
    else if (scrollView.contentOffset.y > 60)
    {
        self.tb.contentInset = UIEdgeInsetsMake(0, 0, 60, 0);
        [self loadMoreData];
    }
}



@end
