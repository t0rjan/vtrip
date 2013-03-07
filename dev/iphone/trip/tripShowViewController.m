//
//  tripShowViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-1-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "tripShowViewController.h"

@interface tripShowViewController ()


@property (strong , nonatomic) UIView *tripCover;
@property (strong , nonatomic) UIImageView *tripCoverImage;
@property (strong , nonatomic) UILabel *tripCoverTitle;
@property (strong , nonatomic) mlViewEleTableLoadingInset *tbHeader;

@property (nonatomic) BOOL isShowingMap;
@property (assign , nonatomic) BOOL isLoading;

@property (assign , nonatomic) NSMutableDictionary *date2photoid;
@property (assign , nonatomic) NSMutableDictionary *photoList;

@end

@implementation tripShowViewController

@synthesize tripInfo;
@synthesize photoList;
@synthesize date2photoid;
@synthesize btn;
@synthesize tb;
@synthesize mapController;
@synthesize modelMyTrip;
@synthesize notice;
@synthesize tripId;


- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        
        self.modelMyTrip = [[modelMyTrip alloc] init];
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    self.view.backgroundColor = [UIColor colorWithPatternImage:[UIImage imageNamed:@"page_bg.png"]];

    
    //nav map btn
    UIBarButtonItem *navBtn = [[UIBarButtonItem alloc] initWithTitle:@"map" style:UIBarButtonItemStyleBordered target:self action:@selector(showMap:)];
    self.navigationItem.rightBarButtonItem = navBtn;
    
    self.mapController = [[tripMapViewController alloc] init];
    self.mapController.view.frame = self.view.frame;
    self.isShowingMap = NO;
    
    //cover
    self.tripCover = [[UIView alloc] initWithFrame:CGRectMake(0, 0, [toolIosSize widthScreen], 150)];
    self.tripCoverImage = [[UIImageView alloc] initWithFrame:self.tripCover.frame];
    [self.tripCover addSubview:self.tripCoverImage];
    UIView *coverTitleBg = [[UIView alloc] initWithFrame:CGRectMake(30, 0, 100, 120)];
    coverTitleBg.backgroundColor = [UIColor colorWithRed:0 green:0 blue:0 alpha:0.6];
    [self.tripCover addSubview:coverTitleBg];
    self.tripCoverTitle = [[UILabel alloc] initWithFrame:CGRectMake(35, 5, 80, 40)];
    self.tripCoverTitle.textColor = [UIColor whiteColor];
    self.tripCoverTitle.backgroundColor = [UIColor clearColor];
    [self.tripCover addSubview:self.tripCoverTitle];
    self.tb.tableHeaderView = self.tripCover;
    
    self.tbHeader = [[mlViewEleTableLoadingInset alloc] init];
    [self.tb addSubview:self.tbHeader];
    self.tb.backgroundColor = [UIColor clearColor];

    [self renderCover];
    
//    [self.navigationController.navigationItem.backBarButtonItem setBackButtonBackgroundImage:[UIImage imageNamed:@"navbackbtn_bg.png"] forState:UIControlStateNormal barMetrics:UIBarMetricsDefault];
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];

    [self renderCover];
    [self reloadData];
}
- (void)viewDidDisappear:(BOOL)animated
{
    if(self.isShowingMap)
    {
        self.navigationItem.rightBarButtonItem.title = @"map";
        self.isShowingMap = NO;
        [self.mapController.view removeFromSuperview];
    }
}





#pragma action
- (void)reloadData
{
    self.date2photoid = nil;
    self.date2photoid = [[NSMutableDictionary alloc] init];
    self.photoList = nil;
    self.photoList = [[NSMutableDictionary alloc] init];
    if(!self.isLoading)
    {
        self.isLoading = YES;
        [self.modelMyTrip fetchTripPhotos:1 withTripId:self.tripId page:1];
        NSDictionary *rs = [self.modelMyTrip getDataDictionary];
        
        [self _formatPhotoData:[rs objectForKey:@"rows"]];
        
        [self.tb reloadData];
        
        [self.notice hideAll];
        [self clearContentInset];
        self.isLoading = NO;
    }
}
- (void)_formatPhotoData:(NSArray *)rows
{
    for (NSDictionary *photo in rows) {
        NSString *date = [toolTime int2Ymd:[[photo objectForKey:@"ctime"] intValue]];
        
        if ([self.date2photoid objectForKey:date] == nil) {
            NSMutableArray *array = [[NSMutableArray alloc] init];
            [array addObject:[photo objectForKey:@"id"]];
            [self.date2photoid setObject:array forKey: date];
        } else {
            NSMutableArray *array = [self.date2photoid objectForKey:date];
            [array addObject:[photo objectForKey:@"id"]];
        }

        [self.photoList setObject:photo forKey:[NSString stringWithFormat:@"%@" ,[photo objectForKey:@"id"]]];
    }

}
- (IBAction)showMap:(id)sender
{
    
    if (self.isShowingMap) {
        self.isShowingMap = NO;
        [UIView animateWithDuration:0.7
                         animations:^{
                             self.navigationItem.rightBarButtonItem.title = @"map";
                             [UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:self.navigationController.view cache:NO];
                             [self.mapController.view removeFromSuperview];
                             
                         }
                         completion:^(BOOL finished){}];
    } else {
        self.isShowingMap = YES;
        self.mapController.photoList = [self.photoList allValues];
                                     self.navigationItem.rightBarButtonItem.title = @"photo";
        [UIView animateWithDuration:0.7
                         animations:^{

                             [UIView setAnimationTransition:UIViewAnimationTransitionFlipFromLeft forView:self.navigationController.view cache:NO];
                             [self.view addSubview:self.mapController.view];
                             
                         }
                         completion:^(BOOL finished){}];
    }
    
}
- (void)showPostionInfo
{
    positionInfoViewController *posiInfo = [[positionInfoViewController alloc] init];
    [self presentViewController:posiInfo animated:YES completion:nil];
}


#pragma view
- (void)clearContentInset
{
    [UIView animateWithDuration:0.3 animations:^{
        tb.contentInset = UIEdgeInsetsMake(0, 0, 0, 0);
    }];

}
- (void)renderCover
{
    self.tripCoverTitle.text = [tripInfo objectForKey:@"title"];
    [self.tripCoverImage setImageWithURL:[NSURL URLWithString:[self.tripInfo objectForKey:@"trpShwCvr_url"]]];
}
- (void)annoShow:(int)photo_id;
{
    self.notice = [[mlViewCustomNotice alloc] initWithFrame:CGRectMake(30, 30, 260, 300)];
    NSDictionary *photoInfo = [self.photoList objectForKey:(NSString *)photo_id];
    
    UILabel *mapTitle = [[UILabel alloc] initWithFrame:CGRectMake(20, 10, 220, 30)];
    mapTitle.backgroundColor = [UIColor clearColor];
    mapTitle.textColor = [UIColor whiteColor];
    mapTitle.textAlignment = UITextAlignmentLeft;
    mapTitle.text = @"我在：故宫";
    mapTitle.font = [UIFont systemFontOfSize:20];
    [self.notice addSubview:mapTitle];
    
    UITextView *mapIntro = [[UITextView alloc] initWithFrame:CGRectMake(20, 200, 220, 60)];
    mapIntro.backgroundColor = [UIColor clearColor];
    mapIntro.textColor = [UIColor whiteColor];
    mapIntro.text = @"最近做个项目，有个点击图片放大的需求，经过高人指点，学会了在UIView的子类中添加点击事件方法的技巧，给各位分享一下";
    [self.notice addSubview:mapIntro];
    
    UIButton *mapMoreBtn = [UIButton buttonWithType:UIButtonTypeCustom];
    mapMoreBtn.frame = CGRectMake(160, 270, 80, 20);
    mapMoreBtn.backgroundColor = [UIColor darkGrayColor];
    UILabel *moreLb = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 80, 20)];
    moreLb.textColor = [UIColor whiteColor];
    moreLb.backgroundColor = [UIColor clearColor];
    moreLb.text = @"查看更多";
    moreLb.font = [UIFont systemFontOfSize:14];
    moreLb.textAlignment = UITextAlignmentCenter;
    [mapMoreBtn addTarget:self action:@selector(showPostionInfo) forControlEvents:UIControlEventTouchUpInside];
    [mapMoreBtn addSubview:moreLb];
    [self.notice addSubview:mapMoreBtn];
    
    MKMapView *map = [[MKMapView alloc] initWithFrame:CGRectMake(20, 40, 220, 150)];
    map.delegate = self;
    map.scrollEnabled = NO;

    CLLocationCoordinate2D loc;
    loc.latitude = [[photoInfo objectForKey:@"latitude"] floatValue];
    loc.longitude = [[photoInfo objectForKey:@"longtitude"] floatValue];
    
    MKCoordinateSpan span;
    span.latitudeDelta = 0.2;
    span.longitudeDelta = 0.2;
    MKCoordinateRegion region = {loc,span};
    [map setRegion:region];
    
    
    MKPointAnnotation *annotation = [[MKPointAnnotation alloc] init];
    annotation.coordinate = loc;
    annotation.title = @"";
    annotation.subtitle = @"这里有好多好多文字，这里有好多好多文字，这里有好多好多文字，这里有好多好多文字，";
    [map addAnnotation:annotation];
    
    [self.notice addSubview:map];
    
    


    
    
    [self.notice showInView:self.view];
}
- (void)showComment:(id)photo_id
{
    if (self.photoCmtC == nil) {
        self.photoCmtC = [[photoCommentViewController alloc] init];
    }

    [self.navigationController pushViewController:self.photoCmtC animated:YES];
}
- (void)showLike:(UILongPressGestureRecognizer *)longPress
{
    if (longPress.state == UIGestureRecognizerStateEnded) {
        UIView *v = (UIView *)longPress.delegate;

        if (self.photoLikeC == nil) {
            self.photoLikeC = [[photoLikeViewController alloc] init];
        }
        [self.navigationController pushViewController:self.photoLikeC animated:YES];

    }
}


#pragma delegate
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return [self.date2photoid count];
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    NSArray *keys = [self.date2photoid allKeys];
    NSString *date = [keys objectAtIndex:section];

    return [[self.date2photoid objectForKey:date] count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{

    NSString *cellId = @"tripShowCellId";
    

    tripShowItemCell *cell = [tableView dequeueReusableCellWithIdentifier:cellId];
    if (cell == nil) {
        cell =[[tripShowItemCell alloc] init];
    }

    NSArray *keys = [self.date2photoid allKeys];
    NSString *date = [keys objectAtIndex:indexPath.section];
    NSInteger *photoid = [[self.date2photoid objectForKey:date] objectAtIndex:indexPath.row];
    cell.imgInfo = [self.photoList objectForKey:photoid];
    [cell setIconDelegate:self];
    [cell render];
    
    UILongPressGestureRecognizer *longPress = [[UILongPressGestureRecognizer alloc] initWithTarget:self action:@selector(showLikes:)];
    longPress.minimumPressDuration = 1.0;
    longPress.delegate = cell;
    
    [cell addGestureRecognizer:longPress];
     
    return cell;
}



- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    NSArray *keys = [self.date2photoid allKeys];
    NSString *date = [keys objectAtIndex:indexPath.section];
    NSInteger *photoid = [[self.date2photoid objectForKey:date] objectAtIndex:indexPath.row];
    NSDictionary *photoInfo = [self.photoList objectForKey:(NSString *)photoid];

    int imgHeight = [[photoInfo objectForKey:@"height_pin"] floatValue];
    int minImgHeight = 120;
    imgHeight = MAX(imgHeight, minImgHeight);
    return imgHeight + 60;

}
- (UIView *)tableView:(UITableView *)tableView viewForHeaderInSection:(NSInteger)section
{
    NSArray *keys = [self.date2photoid allKeys];
    NSString *date = [keys objectAtIndex:section];
    
    UIView *v = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 320, 15)];
    v.backgroundColor = [UIColor yellowColor];
    UILabel *dayNo = [[UILabel alloc] initWithFrame:CGRectMake(5, 1, 80, 13)];
    dayNo.text = date;
    dayNo.backgroundColor = [UIColor clearColor];
    dayNo.font = [UIFont systemFontOfSize:10];
    [v addSubview:dayNo];
    return v;
}
- (CGFloat)tableView:(UITableView *)tableView heightForHeaderInSection:(NSInteger)section
{
    return 15.0f;
}

- (void)scrollViewWillEndDragging:(UIScrollView *)scrollView withVelocity:(CGPoint)velocity targetContentOffset:(inout CGPoint *)targetContentOffset
{
    if(scrollView.contentOffset.y < -61)
    {
        tb.contentInset = UIEdgeInsetsMake(60, 0, 0, 0);
        //[self.notice showInView:self.view];
        NSTimer *timer = [NSTimer scheduledTimerWithTimeInterval:(NSTimeInterval)0.1 target:self selector:@selector(reloadData) userInfo:nil repeats:NO];
    }

}
- (void)mapView:(MKMapView *)mapView didSelectAnnotationView:(MKAnnotationView *)view
{
    CLLocationCoordinate2D a =[view.annotation coordinate];
    NSLog(@"%f %f" , a.latitude , a.longitude);
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)mlViewEleIconTouchUpInside:(id)sender
{
    mlViewEleIconWithNumBase *btn = (mlViewEleIconWithNumBase *)sender;
    int photo_id = [btn.dataPool objectForKey:@"photo_id"];

    if (btn.type == @"anno") {
        [self annoShow:photo_id];
    }else if (btn.type == @"comment"){
        [self showComment:photo_id];
    }else if (btn.type == @"like"){
        //[self showLike:photo_id];
    }
    [btn playSucessAni];

}
- (void)showLikes:(UILongPressGestureRecognizer *)longPress{
    if (longPress.state == UIGestureRecognizerStateEnded) {

        tripShowItemCell *v = (tripShowItemCell *)longPress.delegate;
        int photo_id = (int)[v.imgInfo objectForKey:@"id"];

        if (self.photoLikeC == nil) {
            self.photoLikeC = [[photoLikeViewController alloc] init];
        }
        [self.navigationController pushViewController:self.photoLikeC animated:YES];
        
    }
}
@end
