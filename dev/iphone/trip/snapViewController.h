//
//  snapViewController.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-5.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "cameraViewController.h"
@interface snapViewController : UIViewController

@property (strong , nonatomic) cameraViewController *cameraController;

@property (assign, nonatomic) BOOL is;

@end
