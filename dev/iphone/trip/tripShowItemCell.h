//
//  tripShowItemCell.h
//  trip
//
//  Created by 沈 吾苓 on 13-1-29.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIImageView+WebCache.h"
#import "mlViewElementTimeClock.h"
#import "mlviewEleIconLike.h"
#import "mlViewEleIconComment.h"
#import "mlviewEleIconAnno.h"
#import "tripShowViewController.h"



@interface tripShowItemCell : UITableViewCell<mlViewEleIconDelegate>

@property (copy , nonatomic) NSDictionary *imgInfo;


@property (assign , nonatomic) mlviewEleIconLike *like;
@property (assign , nonatomic) mlViewEleIconComment *comment;
@property (assign , nonatomic) mlviewEleIconAnno *anno;

- (void)render;
- (void)setIconDelegate:(id *)d;
@end
