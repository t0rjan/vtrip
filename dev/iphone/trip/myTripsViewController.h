//
//  myTripsViewController.h
//  trip
//
//  Created by 沈 吾苓 on 13-1-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
//controller
#import "tripShowViewController.h"
#import "newTripViewController.h"
#import "receiveCommentViewController.h"

//model
#import "modelMyTrip.h"
//view
#import "tripItem.h"
#import "mlViewEleTableLoadingInset.h"




@interface myTripsViewController : UIViewController<UITableViewDataSource,UITableViewDelegate>

@property (strong , nonatomic) NSMutableArray *tripList;

@property (strong , nonatomic) tripShowViewController *tripShowC;
@property (strong , nonatomic) modelMyTrip *modelMyTrip;
@property (strong , nonatomic) UITableView *tb;

@end
