//
//  rootViewController.h
//  trip
//
//  Created by 沈 吾苓 on 13-1-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "myTripsViewController.h"
#import "feedViewController.h"
#import "snapViewController.h"

@interface rootViewController : UITabBarController

@property (strong , nonatomic) UINavigationController *myController;
@property (strong , nonatomic) UINavigationController *snsController;
@property (strong , nonatomic) UINavigationController *opController;
@property (strong , nonatomic) UINavigationController *snapController;
@property (strong , nonatomic) UINavigationController *setController;


@end
