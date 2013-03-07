//
//  tripShowViewController.h
//  trip
//
//  Created by 沈 吾苓 on 13-1-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <QuartzCore/QuartzCore.h>
#import <MapKit/MapKit.h>
#import "UIImageView+WebCache.h"

//model
#import "modelMyTrip.h"
//tool
#import "toolIosSize.h"
#import "toolTime.h"
//controller
#import "tripShowItemCell.h"
#import "tripMapViewController.h"
#import "positionInfoViewController.h"
#import "photoCommentViewController.h"
#import "photoLikeViewController.h"
//view
#import "mlViewEleTableLoadingInset.h"
#import "mlViewCustomNotice.h"

@interface tripShowViewController : UIViewController<UITableViewDataSource,UITableViewDelegate,MKMapViewDelegate,mlViewEleIconDelegate>

@property (assign , nonatomic) modelMyTrip *modelMyTrip;

@property (assign , nonatomic) NSInteger *tripId;
@property (strong , nonatomic) NSDictionary *tripInfo;


@property (retain , nonatomic) mlViewCustomNotice *notice;
@property (strong , nonatomic) IBOutlet UITableView *tb;
@property (strong , nonatomic) IBOutlet UIButton *btn;
@property (strong , nonatomic) tripMapViewController    *mapController;
@property (retain , nonatomic) photoCommentViewController *photoCmtC;
@property (retain , nonatomic) photoLikeViewController *photoLikeC;


- (void)render;
- (IBAction)showMap;
@end
