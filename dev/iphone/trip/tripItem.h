//
//  tripItem.h
//  trip
//
//  Created by 沈 吾苓 on 13-1-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIImageView+WebCache.h"
#import <QuartzCore/QuartzCore.h>

@interface tripItem : UITableViewCell

@property (copy , nonatomic) NSString *title;
@property (copy , nonatomic) NSString *time;
@property (copy , nonatomic) NSString *bgimg;



- (void)render;


@end
